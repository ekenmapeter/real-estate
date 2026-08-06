<?php

namespace App\Http\Controllers;

use App\Models\CreditSwap;
use App\Models\Deposit;
use App\Models\PaymentChannel;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DepositController extends Controller
{
    /**
     * Main Deposit Hub Page (/deposit or /deposit/buy-avc)
     */
    public function index(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        // Calculate AVC Balance metrics
        $availableBalance = (float) $user->wallet_balance;
        $estimatedUsd = $availableBalance * 1.00; // 1 AVC = $1.00 USD

        // Calculate Pending AVC from non-finalized deposits
        $pendingAvc = (float) Deposit::where('user_id', $user->id)
            ->whereIn('status', [
                'submitted',
                'awaiting_finance_review',
                'payment_instructions_assigned',
                'awaiting_payment',
                'payment_submitted',
                'under_verification',
                'additional_info_required',
            ])->sum('amount');

        // Calculate Escrow AVC held in P2P Credit Swaps
        $escrowAvc = (float) CreditSwap::where('status', 'in_deal')
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('seller_id', $user->id);
            })->sum('amount');

        // Fetch official active payment channels
        $paymentChannels = PaymentChannel::where('status', 'active')
            ->orderBy('id', 'asc')
            ->get();

        // Recent AVC Activity with filters (All, Finance Deposits, Marketplace Purchases, Pending, Completed, Cancelled)
        $filter = $request->query('filter', 'all');

        $financeDepositsQuery = Deposit::where('user_id', $user->id);
        $marketplaceQuery = CreditSwap::where(function ($q) use ($user) {
            $q->where('user_id', $user->id)
              ->orWhere('buyer_id', $user->id);
        });

        if ($filter === 'finance') {
            $marketplaceQuery->whereRaw('1 = 0'); // Empty
        } elseif ($filter === 'marketplace') {
            $financeDepositsQuery->whereRaw('1 = 0'); // Empty
        } elseif ($filter === 'pending') {
            $financeDepositsQuery->whereIn('status', ['submitted', 'awaiting_finance_review', 'payment_instructions_assigned', 'awaiting_payment', 'payment_submitted', 'under_verification', 'additional_info_required']);
            $marketplaceQuery->whereIn('status', ['open', 'in_deal']);
        } elseif ($filter === 'completed') {
            $financeDepositsQuery->whereIn('status', ['confirmed', 'avc_credited']);
            $marketplaceQuery->where('status', 'completed');
        } elseif ($filter === 'cancelled') {
            $financeDepositsQuery->whereIn('status', ['rejected', 'expired', 'cancelled']);
            $marketplaceQuery->where('status', 'cancelled');
        }

        $financeDeposits = $financeDepositsQuery->orderBy('created_at', 'desc')->take(20)->get();
        $marketplacePurchases = $marketplaceQuery->orderBy('created_at', 'desc')->take(20)->get();

        // Merge & sort activities chronologically
        $recentActivity = collect();
        foreach ($financeDeposits as $dep) {
            $recentActivity->push([
                'type' => 'finance_deposit',
                'id' => $dep->id,
                'code' => $dep->deposit_code,
                'title' => 'Finance Deposit',
                'method' => $dep->methodLabel(),
                'amount' => $dep->amount,
                'avc' => $dep->net_avc ?: $dep->amount,
                'status' => $dep->status,
                'status_label' => $dep->formattedStatusLabel(),
                'badge_class' => $dep->statusBadgeClass(),
                'created_at' => $dep->created_at,
                'url' => route('deposit.show', $dep->id),
            ]);
        }

        foreach ($marketplacePurchases as $swap) {
            $recentActivity->push([
                'type' => 'marketplace_purchase',
                'id' => $swap->id,
                'code' => $swap->listingLabel(),
                'title' => 'Marketplace Purchase',
                'method' => 'Admin Escrow',
                'amount' => $swap->amount,
                'avc' => $swap->amount,
                'status' => $swap->status,
                'status_label' => ucfirst(str_replace('_', ' ', $swap->status)),
                'badge_class' => $swap->status === 'completed' ? 'bg-success' : ($swap->status === 'in_deal' ? 'bg-primary' : 'bg-secondary'),
                'created_at' => $swap->created_at,
                'url' => route('marketplace'),
            ]);
        }

        $recentActivity = $recentActivity->sortByDesc('created_at')->values();

        return view('deposit.index', compact(
            'user',
            'availableBalance',
            'estimatedUsd',
            'pendingAvc',
            'escrowAvc',
            'paymentChannels',
            'recentActivity',
            'filter'
        ));
    }

    /**
     * Display Payment Channel selection & deposit form page (/deposit/channel/{method})
     */
    public function create(string $method)
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $validMethods = ['bank_transfer', 'credit_card', 'wire_transfer', 'crypto'];
        if (!in_array($method, $validMethods)) {
            return redirect()->route('deposit.index')->with('error', 'Invalid payment method selected.');
        }

        $channels = PaymentChannel::where('method_key', $method)
            ->where('status', 'active')
            ->get();

        return view('deposit.channel', compact('user', 'method', 'channels'));
    }

    /**
     * Store new Deposit Request (Section 5, 6, 7, 8 & 9)
     */
    public function store(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $request->validate([
            'payment_method' => 'required|string|in:bank_transfer,credit_card,wire_transfer,crypto',
            'amount' => 'required|numeric|min:10',
            'deposit_currency' => 'nullable|string',
            'country' => 'nullable|string',
            'tx_hash' => 'nullable|string|unique:deposits,tx_hash',
        ]);

        $paymentMethod = $request->payment_method;
        $amount = (float) $request->amount;
        $currency = $request->deposit_currency ?: 'USD';
        $country = $request->country ?: 'Philippines';

        // Locked AVC rate (1.0000 USD per AVC)
        $avcRate = 1.0000;
        $baseUsdValue = $amount; // Assuming USD base
        $grossAvc = $amount / $avcRate;
        $feeAmount = 0.00;
        $netAvc = $grossAvc - $feeAmount;

        $depositCode = 'DEP-' . date('Y') . '-' . sprintf('%06d', rand(1, 999999));

        DB::beginTransaction();
        try {
            $depositData = [
                'user_id' => $user->id,
                'deposit_code' => $depositCode,
                'deposit_type' => 'finance_team',
                'payment_method' => $paymentMethod,
                'amount' => $amount,
                'deposit_amount' => $amount,
                'deposit_currency' => $currency,
                'base_usd_value' => $baseUsdValue,
                'avc_rate' => $avcRate,
                'gross_avc' => $grossAvc,
                'fee_amount' => $feeAmount,
                'net_avc' => $netAvc,
                'rate_locked_at' => now(),
                'country' => $country,
                'currency' => $currency,
                'sender_account_name' => $request->sender_account_name ?? $user->name,
                'sender_bank_name' => $request->sender_bank_name ?? null,
                'sender_account_number' => $request->sender_account_number ?? null,
                'sender_email' => $request->sender_email ?? $user->email,
                'user_notes' => $request->user_notes ?? $request->notes ?? null,
                'reference_id' => 'REF-' . strtoupper(Str::random(8)),
                'status' => 'submitted',
            ];

            // Method-specific data extraction
            if ($paymentMethod === 'crypto') {
                if ($request->filled('tx_hash')) {
                    // Unique TX hash check
                    $existingHash = Deposit::where('tx_hash', $request->tx_hash)->first();
                    if ($existingHash) {
                        return redirect()->back()->with('error', 'This crypto transaction hash has already been submitted for a deposit request.');
                    }
                }
                $depositData['crypto_asset'] = $request->crypto_asset ?? 'USDT';
                $depositData['crypto_network'] = $request->crypto_network ?? 'TRC-20';
                $depositData['tx_hash'] = $request->tx_hash ?? null;
                $depositData['sender_wallet_address'] = $request->sender_wallet_address ?? null;
            } elseif ($paymentMethod === 'credit_card') {
                // Card tokenization simulation (NO raw card number or CVV stored!)
                $cardNumber = preg_replace('/\D/', '', $request->card_number ?? '');
                $depositData['card_last_four'] = substr($cardNumber, -4) ?: '4242';
                $depositData['card_brand'] = $request->card_brand ?: 'Visa';
                $depositData['card_exp_month'] = $request->card_exp_month ?: '12';
                $depositData['card_exp_year'] = $request->card_exp_year ?: '2028';
                $depositData['processor_token'] = 'tok_' . Str::random(16);
                $depositData['processor_session_id'] = 'sess_' . Str::random(20);
                $depositData['status'] = 'payment_submitted'; // Direct card submission
            }

            // Handle optional payment proof upload if provided directly
            if ($request->hasFile('payment_proof')) {
                $file = $request->file('payment_proof');
                $filename = 'proof_' . $depositCode . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/receipts'), $filename);
                $depositData['receipt_proof'] = 'uploads/receipts/' . $filename;
                $depositData['status'] = 'payment_submitted';
            }

            $deposit = Deposit::create($depositData);

            // Log Transaction history
            Transaction::create([
                'user_id' => $user->id,
                'type' => 'deposit',
                'amount' => $amount,
                'reference' => $depositCode,
                'description' => 'Submitted ' . $deposit->methodLabel() . ' Finance Request',
                'status' => 'pending',
            ]);

            DB::commit();

            return redirect()->route('deposit.show', $deposit->id)
                ->with('success', 'Deposit request ' . $depositCode . ' created! Please follow the instructions to complete your payment.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to create deposit request. ' . $e->getMessage());
        }
    }

    /**
     * Dedicated Deposit Request Detail Page (/deposit/{deposit})
     */
    public function show(Deposit $deposit)
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user || ($deposit->user_id !== $user->id && $user->role !== 'admin')) {
            return redirect()->route('deposit.index')->with('error', 'Unauthorized access to deposit request.');
        }

        // Auto-update status if expired
        if ($deposit->isExpired() && !in_array($deposit->status, ['expired', 'avc_credited', 'cancelled', 'rejected'])) {
            $deposit->status = 'expired';
            $deposit->save();
        }

        return view('deposit.show', compact('user', 'deposit'));
    }

    /**
     * Submit Payment Proof for existing deposit request
     */
    public function submitPaymentProof(Request $request, Deposit $deposit)
    {
        /** @var User $user */
        $user = Auth::user();
        if ($deposit->user_id !== $user->id) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        if (!$deposit->canSubmitProof()) {
            return redirect()->back()->with('error', 'Payment proof cannot be submitted for this deposit request at its current stage.');
        }

        $request->validate([
            'payment_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:10240',
            'user_notes' => 'nullable|string',
            'tx_hash' => 'nullable|string|unique:deposits,tx_hash,' . $deposit->id,
        ]);

        if ($request->hasFile('payment_proof')) {
            $file = $request->file('payment_proof');
            $filename = 'proof_' . $deposit->deposit_code . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/receipts'), $filename);
            $deposit->receipt_proof = 'uploads/receipts/' . $filename;
        }

        if ($request->filled('user_notes')) {
            $deposit->user_notes = $request->user_notes;
        }

        if ($request->filled('tx_hash')) {
            $deposit->tx_hash = $request->tx_hash;
        }

        $deposit->status = 'payment_submitted';
        $deposit->save();

        return redirect()->route('deposit.show', $deposit->id)
            ->with('success', 'Payment proof submitted to the Finance Team! Verification is under review.');
    }

    /**
     * User cancels deposit request
     */
    public function cancel(Deposit $deposit)
    {
        /** @var User $user */
        $user = Auth::user();
        if ($deposit->user_id !== $user->id) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        if (in_array($deposit->status, ['avc_credited', 'confirmed', 'cancelled'])) {
            return redirect()->back()->with('error', 'This deposit request cannot be cancelled.');
        }

        $deposit->status = 'cancelled';
        $deposit->save();

        return redirect()->route('deposit.index')->with('success', 'Deposit request ' . $deposit->deposit_code . ' cancelled.');
    }
}
