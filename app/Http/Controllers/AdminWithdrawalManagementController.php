<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\WalletLedger;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminWithdrawalManagementController extends Controller
{
    /**
     * Admin View Withdrawal Requests with Filters
     */
    public function index(Request $request)
    {
        $query = Withdrawal::with(['user', 'savedMethod', 'processedByUser']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('withdrawal_code', 'like', "%{$search}%")
                  ->orWhere('account_name', 'like', "%{$search}%")
                  ->orWhere('transaction_reference', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('method')) {
            $query->where('withdrawal_method', $request->method);
        }

        $withdrawals = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.withdrawals.index', compact('withdrawals'));
    }

    /**
     * Admin approves withdrawal request for processing
     */
    public function approve(Withdrawal $withdrawal)
    {
        $withdrawal->status = 'processing';
        $withdrawal->processed_at = now();
        $withdrawal->processed_by = Auth::id();
        $withdrawal->save();

        return redirect()->back()->with('success', 'Withdrawal request ' . $withdrawal->withdrawal_code . ' approved and sent to Finance processing.');
    }

    /**
     * Admin completes withdrawal (saves TX ref/proof & removes pending balance)
     */
    public function complete(Request $request, Withdrawal $withdrawal)
    {
        /** @var User $admin */
        $admin = Auth::user();

        if ($withdrawal->isCompleted()) {
            return redirect()->back()->with('error', 'This withdrawal request is already marked as completed.');
        }

        $request->validate([
            'transaction_reference' => 'nullable|string',
            'receipt_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
            'admin_notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            /** @var User $lockedUser */
            $lockedUser = User::where('id', $withdrawal->user_id)->lockForUpdate()->firstOrFail();

            $amount = (float) $withdrawal->amount;

            // Deduct pending withdrawals permanently (Section 17)
            $lockedUser->pending_withdrawals = max(0.00, round((float) $lockedUser->pending_withdrawals - $amount, 2));
            $lockedUser->save();

            // Store receipt proof if uploaded
            if ($request->hasFile('receipt_file')) {
                $file = $request->file('receipt_file');
                $filename = 'receipt_' . $withdrawal->withdrawal_code . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/receipts'), $filename);
                $withdrawal->receipt_proof = 'uploads/receipts/' . $filename;
            }

            if ($request->filled('transaction_reference')) {
                $withdrawal->transaction_reference = $request->transaction_reference;
            }

            if ($request->filled('admin_notes')) {
                $withdrawal->admin_notes = $request->admin_notes;
            }

            $withdrawal->status = 'completed';
            $withdrawal->completed_at = now();
            $withdrawal->processed_by = $admin->id;
            $withdrawal->save();

            // Log final double-entry completion in WalletLedger
            WalletLedger::create([
                'user_id' => $lockedUser->id,
                'transaction_type' => 'withdrawal_completed',
                'reference_code' => 'COMP-' . $withdrawal->withdrawal_code,
                'credit_amount' => 0.00,
                'debit_amount' => $amount,
                'balance_before' => (float) $lockedUser->wallet_balance,
                'balance_after' => (float) $lockedUser->wallet_balance,
                'approved_by' => $admin->id,
                'description' => 'Payout sent and completed for withdrawal ' . $withdrawal->withdrawal_code . ' (' . $withdrawal->methodLabel() . ')',
                'status' => 'completed',
            ]);

            DB::commit();

            try {
                app(\App\Services\DocumentService::class)->generate('withdrawal_confirmation', $withdrawal, $lockedUser, [
                    'metadata' => ['related_label' => $withdrawal->withdrawal_code],
                ]);
            } catch (\Throwable $e) {
                report($e);
            }

            return redirect()->back()->with('success', 'Withdrawal request ' . $withdrawal->withdrawal_code . ' marked as COMPLETED! Payout finalized.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to complete withdrawal: ' . $e->getMessage());
        }
    }

    /**
     * Admin rejects withdrawal request (Refunds pending balance to user wallet)
     */
    public function reject(Request $request, Withdrawal $withdrawal)
    {
        $request->validate(['admin_notes' => 'nullable|string']);

        if (in_array($withdrawal->status, ['completed', 'rejected', 'cancelled'])) {
            return redirect()->back()->with('error', 'This withdrawal request cannot be rejected at its current status.');
        }

        DB::beginTransaction();
        try {
            /** @var User $lockedUser */
            $lockedUser = User::where('id', $withdrawal->user_id)->lockForUpdate()->firstOrFail();

            $amount = (float) $withdrawal->amount;
            $balanceBefore = (float) $lockedUser->wallet_balance;
            $balanceAfter = round($balanceBefore + $amount, 2);

            // Refund pending withdrawal back to available balance
            $lockedUser->wallet_balance = $balanceAfter;
            $lockedUser->pending_withdrawals = max(0.00, round((float) $lockedUser->pending_withdrawals - $amount, 2));
            $lockedUser->save();

            $withdrawal->admin_notes = $request->admin_notes ?? 'Withdrawal request rejected by Finance Team.';
            $withdrawal->status = 'rejected';
            $withdrawal->processed_by = Auth::id();
            $withdrawal->save();

            // Log ledger refund
            WalletLedger::create([
                'user_id' => $lockedUser->id,
                'transaction_type' => 'withdrawal_rejected_refund',
                'reference_code' => 'REJ-' . $withdrawal->withdrawal_code,
                'credit_amount' => $amount,
                'debit_amount' => 0.00,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'approved_by' => Auth::id(),
                'description' => 'Withdrawal request ' . $withdrawal->withdrawal_code . ' rejected; refunded ' . number_format($amount, 2) . ' AVC to balance.',
                'status' => 'completed',
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Withdrawal request ' . $withdrawal->withdrawal_code . ' rejected and ' . number_format($amount, 2) . ' AVC refunded to ' . $lockedUser->name . ' balance.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to reject withdrawal: ' . $e->getMessage());
        }
    }
}
