<?php

namespace App\Http\Controllers;

use App\Models\CreditSwap;
use App\Models\SavedWithdrawalMethod;
use App\Models\Transaction;
use App\Models\User;
use App\Models\WalletLedger;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class WithdrawalController extends Controller
{
    /**
     * Main Withdraw / Sell AVC Hub Page (/withdraw)
     */
    public function index(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        // Calculate Balance Metrics (Matching Spec Section 2)
        $availableBalance = (float) $user->wallet_balance;
        $estimatedUsd = $availableBalance * 1.00;
        $pendingWithdrawal = (float) $user->pending_withdrawals;

        $escrowAvc = (float) CreditSwap::where('status', 'in_deal')
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('seller_id', $user->id);
            })->sum('amount');

        $dailyLimit = (float) ($user->daily_withdrawal_limit ?: 10000.00);
        
        // Calculate withdrawals completed or pending today
        $todayWithdrawals = (float) Withdrawal::where('user_id', $user->id)
            ->whereDate('created_at', now()->toDateString())
            ->whereNotIn('status', ['rejected', 'cancelled', 'failed'])
            ->sum('amount');

        $remainingToday = max(0.00, $dailyLimit - $todayWithdrawals);

        // Fetch Saved Payout Methods (Matching Spec Section 8)
        $savedMethods = SavedWithdrawalMethod::where('user_id', $user->id)
            ->orderBy('is_default', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        // Recent Withdrawal Activity with Filters
        $filter = $request->query('filter', 'all');

        $withdrawalsQuery = Withdrawal::where('user_id', $user->id);
        $marketplaceQuery = CreditSwap::where('user_id', $user->id)->where('offer_type', 'sell');

        if ($filter === 'finance') {
            $marketplaceQuery->whereRaw('1 = 0');
        } elseif ($filter === 'marketplace') {
            $withdrawalsQuery->whereRaw('1 = 0');
        } elseif ($filter === 'pending') {
            $withdrawalsQuery->whereIn('status', ['submitted', 'security_verification', 'finance_review', 'approved', 'processing', 'payment_sent', 'more_info_required']);
            $marketplaceQuery->whereIn('status', ['open', 'in_deal']);
        } elseif ($filter === 'completed') {
            $withdrawalsQuery->where('status', 'completed');
            $marketplaceQuery->where('status', 'completed');
        }

        $withdrawals = $withdrawalsQuery->orderBy('created_at', 'desc')->take(15)->get();
        $marketplaceSales = $marketplaceQuery->orderBy('created_at', 'desc')->take(15)->get();

        $recentActivity = collect();
        foreach ($withdrawals as $wdr) {
            $recentActivity->push([
                'type' => 'finance_withdrawal',
                'id' => $wdr->id,
                'code' => $wdr->withdrawal_code,
                'title' => $wdr->methodLabel(),
                'amount' => $wdr->amount,
                'net' => $wdr->estimated_net_payout ?: ($wdr->amount - 2.50),
                'status' => $wdr->status,
                'status_label' => $wdr->formattedStatusLabel(),
                'badge_class' => $wdr->statusBadgeClass(),
                'created_at' => $wdr->created_at,
                'url' => route('withdraw.show', $wdr->id),
            ]);
        }

        foreach ($marketplaceSales as $swap) {
            $recentActivity->push([
                'type' => 'marketplace_sale',
                'id' => $swap->id,
                'code' => $swap->listingLabel(),
                'title' => 'Marketplace Sale',
                'amount' => $swap->amount,
                'net' => $swap->amount,
                'status' => $swap->status,
                'status_label' => ucfirst(str_replace('_', ' ', $swap->status)),
                'badge_class' => $swap->status === 'completed' ? 'bg-success' : 'bg-primary',
                'created_at' => $swap->created_at,
                'url' => route('marketplace'),
            ]);
        }

        $recentActivity = $recentActivity->sortByDesc('created_at')->values();

        return view('withdrawal.index', compact(
            'user',
            'availableBalance',
            'estimatedUsd',
            'pendingWithdrawal',
            'escrowAvc',
            'dailyLimit',
            'remainingToday',
            'savedMethods',
            'recentActivity',
            'filter'
        ));
    }

    /**
     * Submit Withdrawal Request (Section 5, 6, 7, 10 & 11)
     */
    public function store(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $request->validate([
            'withdrawal_method' => 'required|string|in:bank_transfer,mobile_wallet,wire_transfer,crypto',
            'amount' => 'required|numeric|min:10',
            'account_name' => 'required|string',
            'transaction_pin' => 'nullable|string',
            'password' => 'nullable|string',
            'confirm_checkbox' => 'required',
        ]);

        $amount = (float) $request->amount;
        $availableBalance = (float) $user->wallet_balance;

        // 1. Check Available Balance
        if ($amount > $availableBalance) {
            return redirect()->back()->with('error', 'Insufficient available AVC balance for this withdrawal request.');
        }

        // 2. Check Daily Withdrawal Limit
        $todayWithdrawals = (float) Withdrawal::where('user_id', $user->id)
            ->whereDate('created_at', now()->toDateString())
            ->whereNotIn('status', ['rejected', 'cancelled', 'failed'])
            ->sum('amount');

        $dailyLimit = (float) ($user->daily_withdrawal_limit ?: 10000.00);
        if (($todayWithdrawals + $amount) > $dailyLimit) {
            return redirect()->back()->with('error', 'This withdrawal exceeds your remaining daily limit of $' . number_format($dailyLimit - $todayWithdrawals, 2) . ' USD.');
        }

        // 3. Security Verification (PIN or Password Check)
        if ($request->filled('transaction_pin') && $user->transaction_pin) {
            if (!Hash::check($request->transaction_pin, $user->transaction_pin)) {
                return redirect()->back()->with('error', 'Invalid Transaction PIN entered.');
            }
        } elseif ($request->filled('password')) {
            if (!Hash::check($request->password, $user->password)) {
                return redirect()->back()->with('error', 'Invalid account password entered.');
            }
        }

        // Fee calculations (Section 5)
        $avcRate = 1.0000;
        $grossUsdValue = $amount * $avcRate;
        $platformFee = 0.00;
        $processingFee = match ($request->withdrawal_method) {
            'bank_transfer' => 2.50,
            'mobile_wallet' => 1.00,
            'wire_transfer' => 15.00,
            'crypto' => 2.00,
            default => 2.50,
        };
        $estimatedNetPayout = max(0.00, $grossUsdValue - $platformFee - $processingFee);

        $withdrawalCode = 'WDR-' . date('Y') . '-' . sprintf('%06d', rand(1, 999999));

        DB::beginTransaction();
        try {
            // Lock user row for balance shift
            /** @var User $lockedUser */
            $lockedUser = User::where('id', $user->id)->lockForUpdate()->firstOrFail();

            // Shift balance from Available -> Pending Withdrawal (Section 11 & 17)
            $balanceBefore = (float) $lockedUser->wallet_balance;
            $balanceAfter = round($balanceBefore - $amount, 2);

            $lockedUser->wallet_balance = $balanceAfter;
            $lockedUser->pending_withdrawals = round((float) $lockedUser->pending_withdrawals + $amount, 2);
            $lockedUser->save();

            // Create Withdrawal record
            $withdrawal = Withdrawal::create([
                'user_id' => $lockedUser->id,
                'withdrawal_code' => $withdrawalCode,
                'withdrawal_type' => 'finance_team',
                'withdrawal_method' => $request->withdrawal_method,
                'saved_withdrawal_method_id' => $request->saved_withdrawal_method_id ?? null,
                'amount' => $amount,
                'avc_amount' => $amount,
                'avc_rate' => $avcRate,
                'gross_usd_value' => $grossUsdValue,
                'platform_fee' => $platformFee,
                'processing_fee' => $processingFee,
                'estimated_net_payout' => $estimatedNetPayout,
                'payout_currency' => $request->payout_currency ?: 'USD',
                'country' => $request->country ?: 'Philippines',
                'currency' => $request->currency ?: 'PHP',
                'account_name' => $request->account_name,
                'bank_or_provider' => $request->bank_or_provider ?? null,
                'account_number' => $request->account_number ?? null,
                'account_type' => $request->account_type ?? null,
                'swift_bic' => $request->swift_bic ?? null,
                'iban' => $request->iban ?? null,
                'routing_number' => $request->routing_number ?? null,
                'bank_address' => $request->bank_address ?? null,
                'crypto_asset' => $request->crypto_asset ?? null,
                'crypto_network' => $request->crypto_network ?? null,
                'wallet_address' => $request->wallet_address ?? null,
                'destination_tag_memo' => $request->destination_tag_memo ?? null,
                'user_notes' => $request->user_notes ?? null,
                'status' => 'finance_review',
            ]);

            // Optionally save payout account for future express use if requested
            if ($request->boolean('save_payout_account')) {
                SavedWithdrawalMethod::create([
                    'user_id' => $lockedUser->id,
                    'method_key' => $request->withdrawal_method,
                    'title' => ($request->bank_or_provider ?: $request->withdrawal_method) . ' ' . SavedWithdrawalMethod::maskNumber($request->account_number ?: $request->wallet_address),
                    'account_name' => $request->account_name,
                    'bank_or_provider' => $request->bank_or_provider,
                    'account_number' => $request->account_number,
                    'masked_account_number' => SavedWithdrawalMethod::maskNumber($request->account_number ?: $request->wallet_address),
                    'country' => $request->country,
                    'currency' => $request->currency,
                    'wallet_address' => $request->wallet_address,
                ]);
            }

            // Create Transaction history entry
            Transaction::create([
                'user_id' => $lockedUser->id,
                'type' => 'withdrawal',
                'amount' => $amount,
                'reference' => $withdrawalCode,
                'description' => 'Submitted ' . $withdrawal->methodLabel() . ' Request (' . $withdrawalCode . ')',
                'status' => 'pending',
            ]);

            // Log Double-Entry Ledger entry
            WalletLedger::create([
                'user_id' => $lockedUser->id,
                'transaction_type' => 'withdrawal_hold',
                'reference_code' => 'HOLD-' . $withdrawalCode,
                'credit_amount' => 0.00,
                'debit_amount' => $amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'description' => 'AVC placed in pending withdrawal hold for ' . $withdrawalCode,
                'status' => 'completed',
            ]);

            DB::commit();

            try {
                app(\App\Services\DocumentService::class)->generate('withdrawal_request_receipt', $withdrawal, $lockedUser, [
                    'metadata' => ['related_label' => $withdrawalCode],
                ]);
            } catch (\Throwable $e) {
                report($e);
            }

            return redirect()->route('withdraw.show', $withdrawal->id)
                ->with('success', 'Withdrawal request ' . $withdrawalCode . ' submitted successfully! The Finance Team is reviewing your payout.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to process withdrawal request. ' . $e->getMessage());
        }
    }

    /**
     * Dedicated Withdrawal Request Detail Page (/withdraw/{withdrawal})
     */
    public function show(Withdrawal $withdrawal)
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user || ($withdrawal->user_id !== $user->id && $user->role !== 'admin')) {
            return redirect()->route('withdraw.index')->with('error', 'Unauthorized access to withdrawal request.');
        }

        return view('withdrawal.show', compact('user', 'withdrawal'));
    }

    /**
     * User cancels pending withdrawal request (Restores balance)
     */
    public function cancel(Withdrawal $withdrawal)
    {
        /** @var User $user */
        $user = Auth::user();
        if ($withdrawal->user_id !== $user->id) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        if (!$withdrawal->isCancellable()) {
            return redirect()->back()->with('error', 'This withdrawal request cannot be cancelled at its current stage.');
        }

        DB::beginTransaction();
        try {
            /** @var User $lockedUser */
            $lockedUser = User::where('id', $user->id)->lockForUpdate()->firstOrFail();

            $amount = (float) $withdrawal->amount;
            $balanceBefore = (float) $lockedUser->wallet_balance;
            $balanceAfter = round($balanceBefore + $amount, 2);

            // Refund pending withdrawal back to available balance
            $lockedUser->wallet_balance = $balanceAfter;
            $lockedUser->pending_withdrawals = max(0.00, round((float) $lockedUser->pending_withdrawals - $amount, 2));
            $lockedUser->save();

            $withdrawal->status = 'cancelled';
            $withdrawal->save();

            // Log ledger refund
            WalletLedger::create([
                'user_id' => $lockedUser->id,
                'transaction_type' => 'withdrawal_refund',
                'reference_code' => 'RFD-' . $withdrawal->withdrawal_code,
                'credit_amount' => $amount,
                'debit_amount' => 0.00,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'description' => 'Refunded pending withdrawal ' . $withdrawal->withdrawal_code . ' back to available balance',
                'status' => 'completed',
            ]);

            DB::commit();

            return redirect()->route('withdraw.index')->with('success', 'Withdrawal request ' . $withdrawal->withdrawal_code . ' cancelled and ' . number_format($amount, 2) . ' AVC refunded to your balance.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to cancel withdrawal: ' . $e->getMessage());
        }
    }
}
