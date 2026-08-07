<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
use App\Models\PaymentChannel;
use App\Models\User;
use App\Models\WalletLedger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminDepositManagementController extends Controller
{
    /**
     * Admin view deposit requests with filters
     */
    public function index(Request $request)
    {
        $query = Deposit::with(['user', 'paymentChannel', 'ledger', 'creditedByUser']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('deposit_code', 'like', "%{$search}%")
                  ->orWhere('sender_account_name', 'like', "%{$search}%")
                  ->orWhere('tx_hash', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('method')) {
            $query->where('payment_method', $request->method);
        }

        $deposits = $query->orderBy('created_at', 'desc')->paginate(20);
        $paymentChannels = PaymentChannel::all();

        return view('admin.deposits.index', compact('deposits', 'paymentChannels'));
    }

    /**
     * Admin assigns payment account instructions to a user deposit request (Section 9 & 10)
     */
    public function assignInstructions(Request $request, Deposit $deposit)
    {
        $request->validate([
            'beneficiary_name' => 'required|string',
            'account_number' => 'required|string',
            'bank_or_provider' => 'nullable|string',
            'reference_code' => 'nullable|string',
            'expiration_minutes' => 'nullable|integer|min:5|max:1440',
            'instructions_note' => 'nullable|string',
        ]);

        $expirationMinutes = (int) ($request->expiration_minutes ?: 30);
        $referenceCode = $request->reference_code ?: ($deposit->deposit_code);

        $instructions = [
            'method' => $deposit->methodLabel(),
            'beneficiary_name' => $request->beneficiary_name,
            'bank_or_provider' => $request->bank_or_provider,
            'account_number' => $request->account_number,
            'swift_bic' => $request->swift_bic ?? null,
            'iban' => $request->iban ?? null,
            'wallet_address' => $request->wallet_address ?? null,
            'memo' => $request->memo ?? null,
            'reference_code' => $referenceCode,
            'instructions' => $request->instructions_note ?: 'Please send the exact amount. Upload payment proof before the countdown timer expires.',
            'assigned_at' => now()->toDateTimeString(),
        ];

        $deposit->admin_instructions = $instructions;
        $deposit->expires_at = now()->addMinutes($expirationMinutes);
        $deposit->status = 'payment_instructions_assigned';
        $deposit->save();

        try {
            app(\App\Services\DocumentService::class)->generate('deposit_confirmation', $deposit, $deposit->user, [
                'metadata' => ['related_label' => $deposit->deposit_code],
            ]);
        } catch (\Throwable $e) {
            report($e);
        }

        return redirect()->back()->with('success', 'Payment instructions assigned to deposit request ' . $deposit->deposit_code . ' (Expires in ' . $expirationMinutes . ' mins).');
    }

    /**
     * Admin approves deposit and credits AVC wallet via double-entry ledger (Section 18)
     */
    public function creditAvc(Request $request, Deposit $deposit)
    {
        /** @var User $admin */
        $admin = Auth::user();

        if ($deposit->isCredited()) {
            return redirect()->back()->with('error', 'This deposit request has already been credited with AVC.');
        }

        $creditAmount = (float) ($deposit->net_avc ?: $deposit->amount);
        $referenceCode = 'CRD-' . $deposit->deposit_code;
        $description = 'AVC credited for Finance Team deposit ' . $deposit->deposit_code . ' (' . $deposit->methodLabel() . ')';

        DB::beginTransaction();
        try {
            $user = User::findOrFail($deposit->user_id);

            // Execute atomic ledger credit (prevents double crediting!)
            $ledger = WalletLedger::creditUser(
                $user,
                $creditAmount,
                'deposit_credit',
                $referenceCode,
                $description,
                $deposit->id,
                $admin->id
            );

            // Update deposit status
            $deposit->status = 'avc_credited';
            $deposit->credited_at = now();
            $deposit->credited_by = $admin->id;
            if ($request->filled('admin_notes')) {
                $deposit->admin_notes = $request->admin_notes;
            }
            $deposit->save();

            DB::commit();

            try {
                app(\App\Services\DocumentService::class)->generate('deposit_receipt', $deposit, $user, [
                    'metadata' => ['related_label' => $deposit->deposit_code],
                ]);
            } catch (\Throwable $e) {
                report($e);
            }

            return redirect()->back()->with('success', 'Successfully credited ' . number_format($creditAmount, 2) . ' AVC to ' . $user->name . ' (' . $deposit->deposit_code . ')!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to credit AVC: ' . $e->getMessage());
        }
    }

    /**
     * Admin requests additional information from user
     */
    public function requestInfo(Request $request, Deposit $deposit)
    {
        $request->validate(['admin_notes' => 'required|string']);

        $deposit->admin_notes = $request->admin_notes;
        $deposit->status = 'additional_info_required';
        $deposit->save();

        return redirect()->back()->with('success', 'Requested additional information for deposit ' . $deposit->deposit_code);
    }

    /**
     * Admin rejects deposit request
     */
    public function reject(Request $request, Deposit $deposit)
    {
        $deposit->admin_notes = $request->admin_notes ?? 'Payment verification failed.';
        $deposit->status = 'rejected';
        $deposit->save();

        return redirect()->back()->with('success', 'Deposit request ' . $deposit->deposit_code . ' rejected.');
    }

    /**
     * Admin extends payment timer
     */
    public function extendTimer(Request $request, Deposit $deposit)
    {
        $minutes = (int) ($request->minutes ?: 30);
        $deposit->expires_at = ($deposit->expires_at && $deposit->expires_at->isFuture() ? $deposit->expires_at : now())->addMinutes($minutes);
        if ($deposit->status === 'expired') {
            $deposit->status = 'payment_instructions_assigned';
        }
        $deposit->save();

        return redirect()->back()->with('success', 'Extended payment timer for ' . $deposit->deposit_code . ' by ' . $minutes . ' minutes.');
    }
}
