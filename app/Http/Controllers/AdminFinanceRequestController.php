<?php

namespace App\Http\Controllers;

use App\Models\FinanceRequest;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminFinanceRequestController extends Controller
{
    /**
     * Admin Listing Page (/admin/finance-requests)
     */
    public function index(Request $request)
    {
        $type = $request->query('type', 'all');
        $status = $request->query('status', 'all');
        $search = $request->query('search', '');

        $query = FinanceRequest::with('user');

        if ($type && $type !== 'all') {
            $query->where('type', $type);
        }

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('request_id', 'like', "%{$search}%")
                  ->orWhere('sender_name', 'like', "%{$search}%")
                  ->orWhere('sender_email', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($u) use ($search) {
                      $u->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $requests = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('admin.finance-requests.index', compact('requests', 'type', 'status', 'search'));
    }

    /**
     * Admin Detail Inspection Page (/admin/finance-requests/{id})
     */
    public function show($id)
    {
        $financeRequest = FinanceRequest::with('user')->findOrFail($id);

        return view('admin.finance-requests.show', compact('financeRequest'));
    }

    /**
     * Admin Assigns Payment Instructions (Step 5 in Image 2)
     */
    public function assignInstructions(Request $request, $id)
    {
        $financeRequest = FinanceRequest::findOrFail($id);

        $validated = $request->validate([
            'assigned_payment_method' => 'required|string|max:100',
            'assigned_account_name' => 'required|string|max:150',
            'assigned_account_number' => 'required|string|max:150',
            'assigned_reference' => 'required|string|max:100',
            'expiration_minutes' => 'required|integer|min:5|max:1440', // e.g. 20 minutes
            'assigned_instructions' => 'nullable|string|max:1000',
        ]);

        $financeRequest->assigned_payment_method = $validated['assigned_payment_method'];
        $financeRequest->assigned_account_name = $validated['assigned_account_name'];
        $financeRequest->assigned_account_number = $validated['assigned_account_number'];
        $financeRequest->assigned_reference = $validated['assigned_reference'];
        $financeRequest->assigned_instructions = $validated['assigned_instructions'] ?? 'Please send the exact amount. Do not include any remarks. Upload your payment receipt before the timer expires.';
        $financeRequest->expires_at = Carbon::now()->addMinutes((int) $validated['expiration_minutes']);
        $financeRequest->status = 'payment_instructions_assigned';
        $financeRequest->save();

        return redirect()->back()->with('success', 'Payment instructions & expiration timer sent to user successfully!');
    }

    /**
     * Admin Approves Request & Credits Wallet Balance
     */
    public function approve(Request $request, $id)
    {
        $financeRequest = FinanceRequest::with('user')->findOrFail($id);

        if ($financeRequest->status === 'completed') {
            return redirect()->back()->with('error', 'Request is already completed.');
        }

        DB::transaction(function () use ($financeRequest, $request) {
            $user = $financeRequest->user;
            $amount = (float) $financeRequest->amount;

            if ($financeRequest->type === 'deposit') {
                // Credit user's wallet balance
                $user->wallet_balance = (float) $user->wallet_balance + $amount;
                $user->save();

                // Create ledger transaction
                Transaction::create([
                    'user_id' => $user->id,
                    'type' => 'deposit',
                    'direction' => 'credit',
                    'category' => 'deposit',
                    'payment_method' => 'Finance Team (' . $financeRequest->payment_method . ')',
                    'amount' => $amount,
                    'fiat_equivalent' => $amount,
                    'reference' => $financeRequest->request_id,
                    'description' => 'Finance Team Deposit Assistance via ' . $financeRequest->payment_method,
                    'notes' => $request->input('admin_notes', 'Approved by Finance Team'),
                    'status' => 'completed',
                    'receipt_proof' => $financeRequest->payment_evidence,
                    'related_type' => FinanceRequest::class,
                    'related_id' => $financeRequest->id,
                ]);
            } else { // Withdrawal
                // Deduct balance or unlock pending withdrawals
                if ($user->pending_withdrawals && $user->pending_withdrawals >= $amount) {
                    $user->pending_withdrawals = max(0, (float) $user->pending_withdrawals - $amount);
                } else {
                    $user->wallet_balance = max(0, (float) $user->wallet_balance - $amount);
                }
                $user->save();

                Transaction::create([
                    'user_id' => $user->id,
                    'type' => 'withdrawal',
                    'direction' => 'debit',
                    'category' => 'withdrawal',
                    'payment_method' => 'Finance Team (' . $financeRequest->payment_method . ')',
                    'amount' => $amount,
                    'fiat_equivalent' => $amount,
                    'reference' => $financeRequest->request_id,
                    'description' => 'Finance Team Cash-Out Assistance to ' . $financeRequest->sender_account,
                    'notes' => $request->input('admin_notes', 'Processed & Paid by Finance Team'),
                    'status' => 'completed',
                    'related_type' => FinanceRequest::class,
                    'related_id' => $financeRequest->id,
                ]);
            }

            $financeRequest->status = 'completed';
            $financeRequest->admin_notes = $request->input('admin_notes', 'Approved by Admin');
            $financeRequest->completed_at = Carbon::now();
            $financeRequest->save();

            try {
                app(\App\Services\DocumentService::class)->generate('finance_request_receipt', $financeRequest, $user, [
                    'metadata' => ['related_label' => $financeRequest->request_id],
                ]);
            } catch (\Throwable $e) {
                report($e);
            }
        });

        return redirect()->back()->with('success', 'Finance request approved! User wallet has been updated.');
    }

    /**
     * Admin Rejects Request
     */
    public function reject(Request $request, $id)
    {
        $financeRequest = FinanceRequest::findOrFail($id);

        $financeRequest->status = 'rejected';
        $financeRequest->admin_notes = $request->input('admin_notes', 'Rejected by Finance Team');
        $financeRequest->save();

        return redirect()->back()->with('success', 'Finance request has been rejected.');
    }
}
