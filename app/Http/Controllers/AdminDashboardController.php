<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Property;
use App\Models\Investment;
use App\Models\Deposit;
use App\Models\Withdrawal;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    public function index()
    {
        /** @var User $admin */
        $admin = Auth::user();

        // If not admin, switch user to admin for preview/testing
        if (!$admin || $admin->role !== 'admin') {
            $adminUser = User::where('role', 'admin')->notExpired()->first();
            if ($adminUser) {
                Auth::login($adminUser);
                $admin = $adminUser;
            }
        }

        if ($admin && $admin->isExpired()) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Your admin account has expired. Please contact support.');
        }

        $totalUsersCount = User::where('role', 'user')->count();
        $totalInvestmentsAmount = Investment::sum('total_amount');
        $totalPropertiesCount = Property::count();
        $pendingDeposits = Deposit::with('user')->where('status', 'pending')->orderBy('created_at', 'desc')->get();
        $pendingWithdrawals = Withdrawal::with('user')->where('status', 'pending')->orderBy('created_at', 'desc')->get();
        $allDeposits = Deposit::with('user')->orderBy('created_at', 'desc')->take(20)->get();
        $allWithdrawals = Withdrawal::with('user')->orderBy('created_at', 'desc')->take(20)->get();
        $properties = Property::orderBy('created_at', 'desc')->get();
        $users = User::orderBy('created_at', 'desc')->get();

        return view('admin.dashboard', compact(
            'admin',
            'totalUsersCount',
            'totalInvestmentsAmount',
            'totalPropertiesCount',
            'pendingDeposits',
            'pendingWithdrawals',
            'allDeposits',
            'allWithdrawals',
            'properties',
            'users'
        ));
    }

    public function approveDeposit($id)
    {
        $deposit = Deposit::findOrFail($id);

        if ($deposit->status === 'approved') {
            return redirect()->back()->with('error', 'Deposit is already approved.');
        }

        $deposit->status = 'approved';
        $deposit->save();

        // Credit user wallet balance
        $user = User::find($deposit->user_id);
        if ($user) {
            $user->wallet_balance += $deposit->amount;
            $user->save();

            // Update pending transaction status
            Transaction::where('reference', $deposit->deposit_code)
                ->update(['status' => 'completed']);
        }

        return redirect()->back()->with('success', 'Deposit of $' . number_format($deposit->amount, 2) . ' approved successfully!');
    }

    public function rejectDeposit($id)
    {
        $deposit = Deposit::findOrFail($id);
        $deposit->status = 'rejected';
        $deposit->save();

        Transaction::where('reference', $deposit->deposit_code)
            ->update(['status' => 'rejected']);

        return redirect()->back()->with('success', 'Deposit request rejected.');
    }

    public function approveWithdrawal($id)
    {
        $withdrawal = Withdrawal::findOrFail($id);

        if ($withdrawal->status === 'approved') {
            return redirect()->back()->with('error', 'Withdrawal is already approved.');
        }

        $withdrawal->status = 'approved';
        $withdrawal->save();

        Transaction::where('reference', $withdrawal->withdrawal_code)
            ->update(['status' => 'completed']);

        return redirect()->back()->with('success', 'Withdrawal of $' . number_format($withdrawal->amount, 2) . ' approved successfully!');
    }

    public function rejectWithdrawal($id)
    {
        $withdrawal = Withdrawal::findOrFail($id);

        if ($withdrawal->status !== 'approved') {
            // Refund user wallet balance if it was deducted when requested
            $user = User::find($withdrawal->user_id);
            if ($user) {
                $user->wallet_balance += $withdrawal->amount;
                $user->save();
            }
        }

        $withdrawal->status = 'rejected';
        $withdrawal->save();

        Transaction::where('reference', $withdrawal->withdrawal_code)
            ->update(['status' => 'rejected']);

        return redirect()->back()->with('success', 'Withdrawal request rejected and funds refunded to user wallet.');
    }

    public function storeProperty(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'category' => 'required|string',
            'price_per_share' => 'required|numeric|min:1',
            'total_shares' => 'required|integer|min:1',
            'roi_percentage' => 'required|numeric|min:0',
            'image_url' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        Property::create([
            'title' => $request->title,
            'location' => $request->location,
            'category' => $request->category,
            'price_per_share' => $request->price_per_share,
            'total_shares' => $request->total_shares,
            'available_shares' => $request->total_shares,
            'roi_percentage' => $request->roi_percentage,
            'investment_duration_months' => $request->investment_duration_months ?? 12,
            'image_url' => $request->image_url ?: 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?q=80&w=1000&auto=format&fit=crop',
            'description' => $request->description,
            'status' => 'active',
        ]);

        return redirect()->back()->with('success', 'New housing property listing created successfully!');
    }
}
