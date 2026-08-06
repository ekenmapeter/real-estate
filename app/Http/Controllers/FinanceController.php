<?php

namespace App\Http\Controllers;

use App\Models\CreditSwap;
use App\Models\Deposit;
use App\Models\FinanceRequest;
use App\Models\SavedWithdrawalMethod;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FinanceController extends Controller
{
    /**
     * Finance Center Overview Page (/finance)
     * Matches the design of Deposit/Withdrawal hubs
     */
    public function overview()
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        // Available Balance Metrics
        $availableBalance = (float) $user->wallet_balance;
        $estimatedUsd = $availableBalance * 1.00;

        // Pending Deposits
        $pendingDeposits = (float) Deposit::where('user_id', $user->id)
            ->whereIn('status', ['submitted', 'awaiting_finance_review', 'payment_instructions_assigned', 'awaiting_payment', 'payment_submitted', 'under_verification'])
            ->sum('amount');

        // Pending Withdrawals
        $pendingWithdrawals = (float) Withdrawal::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'processing', 'awaiting_approval'])
            ->sum('amount');

        // AVC in Escrow
        $escrowAvc = (float) CreditSwap::where('status', 'in_deal')
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('seller_id', $user->id);
            })->sum('amount');

        // Lifetime Metrics
        $lifetimeDeposits = (float) Deposit::where('user_id', $user->id)->where('status', 'completed')->sum('amount');
        $lifetimeWithdrawals = (float) Withdrawal::where('user_id', $user->id)->where('status', 'completed')->sum('amount');
        $totalFeesPaid = (float) Transaction::where('user_id', $user->id)->where('category', 'fees')->sum('fee_amount');

        // Dedicated Finance Team Requests
        $financeTeamRequests = FinanceRequest::where('user_id', $user->id)->orderBy('created_at', 'desc')->take(5)->get();

        // Finance Team Requests (Recent Deposits & Withdrawals combined)
        $recentDeposits = Deposit::where('user_id', $user->id)->orderBy('created_at', 'desc')->take(5)->get();
        $recentWithdrawals = Withdrawal::where('user_id', $user->id)->orderBy('created_at', 'desc')->take(5)->get();

        // Saved Payment Methods
        $savedMethods = SavedWithdrawalMethod::where('user_id', $user->id)->get();
        $methodsCount = [
            'bank_account' => $savedMethods->where('method_type', 'bank_transfer')->count(),
            'mobile_wallet' => $savedMethods->where('method_type', 'gcash')->count(),
            'crypto_wallet' => $savedMethods->where('method_type', 'crypto')->count(),
            'card' => 0,
        ];

        // Recent Financial Transactions Ledger
        $recentTransactions = Transaction::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Withdrawal Limits
        $dailyLimit = (float) ($user->daily_withdrawal_limit ?? 50000.00);
        $todayWithdrawn = (float) Withdrawal::where('user_id', $user->id)
            ->whereDate('created_at', now()->toDateString())
            ->whereIn('status', ['completed', 'processing', 'pending'])
            ->sum('amount');
        $remainingLimit = max(0, $dailyLimit - $todayWithdrawn);

        return view('finance.overview', compact(
            'user',
            'availableBalance',
            'estimatedUsd',
            'pendingDeposits',
            'pendingWithdrawals',
            'escrowAvc',
            'lifetimeDeposits',
            'lifetimeWithdrawals',
            'totalFeesPaid',
            'financeTeamRequests',
            'recentDeposits',
            'recentWithdrawals',
            'savedMethods',
            'methodsCount',
            'recentTransactions',
            'dailyLimit',
            'todayWithdrawn',
            'remainingLimit'
        ));
    }

    /**
     * Complete Transaction History Ledger Page (/finance/transactions)
     */
    public function transactions(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $category = $request->query('category', 'all');
        $status = $request->query('status', 'all');
        $search = $request->query('search', '');
        $dateFrom = $request->query('date_from');
        $dateTo = $request->query('date_to');

        $query = Transaction::where('user_id', $user->id);

        if ($category && $category !== 'all') {
            $query->where('category', $category);
        }

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('payment_method', 'like', "%{$search}%");
            });
        }

        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $transactions = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        // Summary Stat Metrics for History Header
        $totalTransactionsCount = Transaction::where('user_id', $user->id)->count();
        $totalCredits = (float) Transaction::where('user_id', $user->id)->credits()->sum('amount');
        $totalDebits = (float) Transaction::where('user_id', $user->id)->debits()->sum('amount');
        $netFlow = $totalCredits - $totalDebits;

        $pendingCount = Transaction::where('user_id', $user->id)->whereIn('status', ['pending', 'processing', 'submitted', 'under_review'])->count();

        return view('finance.transactions', compact(
            'transactions',
            'category',
            'status',
            'search',
            'dateFrom',
            'dateTo',
            'totalTransactionsCount',
            'totalCredits',
            'totalDebits',
            'netFlow',
            'pendingCount'
        ));
    }

    /**
     * Transaction Detail View (/finance/transactions/{transaction})
     */
    public function transactionShow(Transaction $transaction)
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user || $transaction->user_id !== $user->id) {
            abort(403, 'Unauthorized access to transaction record.');
        }

        return view('finance.transaction-show', compact('transaction'));
    }

    /**
     * Export Transactions to CSV
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user) {
            abort(401);
        }

        $category = $request->query('category', 'all');
        $status = $request->query('status', 'all');

        $query = Transaction::where('user_id', $user->id);
        if ($category && $category !== 'all') {
            $query->where('category', $category);
        }
        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        $transactions = $query->orderBy('created_at', 'desc')->get();

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=transactions_export_" . date('Y-m-d') . ".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () use ($transactions) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Reference', 'Date/Time', 'Category', 'Type', 'Direction', 'Payment Method', 'Amount (AVC)', 'Fiat Equivalent (USD)', 'Fee (USD)', 'Status', 'Description']);

            foreach ($transactions as $t) {
                fputcsv($file, [
                    $t->reference,
                    $t->created_at->format('Y-m-d H:i:s'),
                    ucfirst($t->category ?? 'N/A'),
                    ucfirst($t->type),
                    strtoupper($t->direction ?? ($t->isCredit() ? 'CREDIT' : 'DEBIT')),
                    $t->payment_method ?? 'N/A',
                    $t->signedAmount(),
                    '$' . number_format($t->fiat_equivalent ?? $t->amount, 2),
                    '$' . number_format($t->fee_amount ?? 0, 2),
                    $t->formattedStatusLabel(),
                    $t->description
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
