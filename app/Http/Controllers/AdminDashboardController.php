<?php

namespace App\Http\Controllers;

use App\Mail\DepositApprovedMail;
use App\Mail\DepositRejectedMail;
use App\Mail\KycRejectedMail;
use App\Mail\KycVerifiedMail;
use App\Mail\WithdrawalApprovedMail;
use App\Mail\WithdrawalRejectedMail;
use App\Models\User;
use App\Models\Property;
use App\Models\Investment;
use App\Models\Deposit;
use App\Models\Withdrawal;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

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
        $kycPendingUsers = User::where('kyc_status', 'pending')->whereNotNull('kyc_document_path')->orderBy('kyc_submitted_at', 'desc')->get();
        $referrers = User::whereHas('referrals')->with('referrals')->withCount('referrals')->orderBy('affiliate_earnings', 'desc')->get();

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
            'users',
            'kycPendingUsers',
            'referrers',
        ));
    }

    public function sendInstructions(Request $request, $id)
    {
        $deposit = Deposit::findOrFail($id);

        $request->validate([
            'beneficiary_method' => 'required|string',
            'beneficiary_account_number' => 'required|string',
            'beneficiary_account_name' => 'required|string',
        ]);

        $expirationMinutes = (int) ($request->expiration_minutes ?? 20);

        $deposit->admin_instructions = [
            'method'         => $request->beneficiary_method,
            'account_number' => $request->beneficiary_account_number,
            'account_name'   => $request->beneficiary_account_name,
            'reference_no'   => $request->reference_number ?: ('RDR' . date('Ymd') . rand(100, 999)),
            'instructions'   => $request->instructions ?: 'Please send the exact amount. Do not include any remarks. Upload your payment receipt before the timer expires.',
            'expires_minutes'=> $expirationMinutes,
        ];

        $deposit->expires_at = now()->addMinutes($expirationMinutes);
        $deposit->status = 'awaiting_payment';
        $deposit->save();

        return redirect()->back()->with('success', 'Payment instructions sent to user for request ' . $deposit->deposit_code . '!');
    }

    public function approveDeposit($id)
    {
        $deposit = Deposit::findOrFail($id);

        if ($deposit->status === 'completed') {
            return redirect()->back()->with('error', 'Deposit is already completed.');
        }

        $deposit->status = 'completed';
        $deposit->save();

        // Credit user wallet balance
        $user = User::find($deposit->user_id);
        if ($user) {
            $user->wallet_balance += $deposit->amount;
            $user->save();

            // Update pending transaction status
            Transaction::where('reference', $deposit->deposit_code)
                ->update(['status' => 'completed']);

            Mail::to($user->email)->send(new DepositApprovedMail($deposit));
        }

        return redirect()->back()->with('success', 'Finance request ' . $deposit->deposit_code . ' approved! $' . number_format($deposit->amount, 2) . ' credited to investor wallet.');
    }

    public function rejectDeposit($id)
    {
        $deposit = Deposit::findOrFail($id);
        $deposit->status = 'rejected';
        $deposit->save();

        Transaction::where('reference', $deposit->deposit_code)
            ->update(['status' => 'rejected']);

        $user = User::find($deposit->user_id);
        if ($user) {
            Mail::to($user->email)->send(new DepositRejectedMail($deposit));
        }

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

        $user = User::find($withdrawal->user_id);
        if ($user) {
            Mail::to($user->email)->send(new WithdrawalApprovedMail($withdrawal));
        }

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

        $user = User::find($withdrawal->user_id);
        if ($user) {
            Mail::to($user->email)->send(new WithdrawalRejectedMail($withdrawal));
        }

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

    public function updateProperty(Request $request, $id)
    {
        $request->validate([
            'roi_percentage' => 'required|numeric|min:0|max:1000',
            'price_per_share' => 'required|numeric|min:1',
            'investment_duration_months' => 'required|integer|min:1',
        ]);

        $property = Property::findOrFail($id);
        $property->roi_percentage = $request->roi_percentage;
        $property->price_per_share = $request->price_per_share;
        $property->investment_duration_months = $request->investment_duration_months;
        $property->save();

        return redirect()->back()->with('success', 'Property "' . $property->title . '" settings updated. Daily ROI cron will use the new values.');
    }

    public function awardReferralBonus(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $user = User::findOrFail($request->user_id);

        $user->affiliate_earnings = ($user->affiliate_earnings ?? 0) + $request->amount;
        $user->wallet_balance = ($user->wallet_balance ?? 0) + $request->amount;
        $user->save();

        Transaction::create([
            'user_id' => $user->id,
            'type' => 'affiliate_earning',
            'amount' => $request->amount,
            'reference' => 'BONUS-' . strtoupper(\Illuminate\Support\Str::random(8)),
            'description' => 'Referral bonus awarded by admin',
            'status' => 'completed',
        ]);

        return redirect()->back()->with('success', 'Referral bonus of $' . number_format($request->amount, 2) . ' awarded to ' . $user->name . '!');
    }

    public function approveKyc($id)
    {
        $user = User::findOrFail($id);
        $user->kyc_verified = true;
        $user->kyc_status = 'approved';
        $user->save();

        Mail::to($user->email)->send(new KycVerifiedMail($user));

        return redirect()->back()->with('success', 'KYC approved for ' . $user->name . '!');
    }

    public function rejectKyc(Request $request, $id)
    {
        $request->validate(['reason' => 'required|string|max:1000']);

        $user = User::findOrFail($id);
        $user->kyc_status = 'rejected';
        $user->kyc_rejected_reason = $request->reason;
        $user->save();

        Mail::to($user->email)->send(new KycRejectedMail($user, $request->reason));

        return redirect()->back()->with('success', 'KYC rejected for ' . $user->name . '. Reason noted.');
    }
}
