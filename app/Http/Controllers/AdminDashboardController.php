<?php

namespace App\Http\Controllers;

use App\Mail\DepositApprovedMail;
use App\Mail\DepositRejectedMail;
use App\Mail\KycRejectedMail;
use App\Mail\KycVerifiedMail;
use App\Mail\WithdrawalApprovedMail;
use App\Mail\WithdrawalRejectedMail;
use App\Mail\CardApprovedMail;
use App\Mail\CardRejectedMail;
use App\Models\User;
use App\Models\Property;
use App\Models\Project;
use App\Models\Investment;
use App\Models\Deposit;
use App\Models\Withdrawal;
use App\Models\Transaction;
use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminDashboardController extends Controller
{
    public function index()
    {
        /** @var User $admin */
        $admin = Auth::user();

        if ($admin->isExpired()) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Your admin account has expired. Please contact support.');
        }

        $totalUsersCount = User::where('role', 'user')->count();
        $totalInvestmentsAmount = Investment::sum('total_amount');
        $totalPropertiesCount = Property::count();
        $totalProjectsCount = Project::count();
        $pendingDeposits = Deposit::with('user')->where('status', 'pending')->orderBy('created_at', 'desc')->get();
        $pendingWithdrawals = Withdrawal::with('user')->where('status', 'pending')->orderBy('created_at', 'desc')->get();
        $allDeposits = Deposit::with('user')->orderBy('created_at', 'desc')->take(20)->get();
        $allWithdrawals = Withdrawal::with('user')->orderBy('created_at', 'desc')->take(20)->get();
        $properties = Property::orderBy('created_at', 'desc')->get();
        $projects = Project::withCount('investments')->orderBy('created_at', 'desc')->get();
        $users = User::with(['investments.property', 'deposits', 'withdrawals', 'transactions', 'referrals'])
            ->where('role', 'user')
            ->orderBy('created_at', 'desc')
            ->get();
        $kycPendingUsers = User::where('kyc_status', 'pending')->whereNotNull('kyc_document_path')->orderBy('kyc_submitted_at', 'desc')->get();
        $referrers = User::whereHas('referrals')->with('referrals')->withCount('referrals')->orderBy('affiliate_earnings', 'desc')->get();
        $cards = Card::with('user')->orderBy('created_at', 'desc')->get();

        return view('admin.dashboard', compact(
            'admin',
            'totalUsersCount',
            'totalInvestmentsAmount',
            'totalPropertiesCount',
            'totalProjectsCount',
            'pendingDeposits',
            'pendingWithdrawals',
            'allDeposits',
            'allWithdrawals',
            'properties',
            'projects',
            'users',
            'kycPendingUsers',
            'referrers',
            'cards',
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
            'price' => 'nullable|numeric|min:1',
            'price_per_share' => 'required|numeric|min:1',
            'total_shares' => 'required|integer|min:1',
            'roi_percentage' => 'required|numeric|min:0',
            'image_url' => 'nullable|string',
            'description' => 'nullable|string',
            'status' => 'nullable|string|in:active,sold_out,upcoming',
        ]);

        Property::create([
            'title' => $request->title,
            'location' => $request->location,
            'category' => $request->category,
            'price' => $request->price,
            'price_per_share' => $request->price_per_share,
            'total_shares' => $request->total_shares,
            'available_shares' => $request->total_shares,
            'roi_percentage' => $request->roi_percentage,
            'investment_duration_months' => $request->investment_duration_months ?? 12,
            'image_url' => $request->image_url ?: 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?q=80&w=1000&auto=format&fit=crop',
            'description' => $request->description,
            'status' => $request->status ?: 'active',
        ]);

        return redirect()->route('admin.dashboard', ['tab' => 'properties'])->with('success', 'New housing property listing created successfully!');
    }

    public function updateProperty(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'category' => 'required|string',
            'price' => 'nullable|numeric|min:1',
            'price_per_share' => 'required|numeric|min:1',
            'total_shares' => 'required|integer|min:1',
            'roi_percentage' => 'required|numeric|min:0|max:1000',
            'investment_duration_months' => 'required|integer|min:1',
            'image_url' => 'nullable|string',
            'description' => 'nullable|string',
            'status' => 'nullable|string|in:active,sold_out,upcoming',
        ]);

        $property = Property::findOrFail($id);
        $property->title = $request->title;
        $property->location = $request->location;
        $property->category = $request->category;
        $property->price = $request->price;
        $property->price_per_share = $request->price_per_share;
        $property->total_shares = $request->total_shares;
        $property->available_shares = min($request->total_shares, $property->available_shares);
        $property->roi_percentage = $request->roi_percentage;
        $property->investment_duration_months = $request->investment_duration_months;
        $property->image_url = $request->image_url;
        $property->description = $request->description;
        $property->status = $request->status ?: $property->status;
        $property->save();

        return redirect()->route('admin.dashboard', ['tab' => 'properties'])->with('success', 'Property "' . $property->title . '" updated successfully.');
    }

    public function editProperty($id)
    {
        $property = Property::findOrFail($id);

        return view('admin.property-edit', compact('property'));
    }

    public function deleteProperty($id)
    {
        $property = Property::findOrFail($id);
        $investmentsCount = $property->investments()->count();

        $property->delete();

        return redirect()->back()->with('success', 'Property "' . $property->title . '" deleted' . ($investmentsCount > 0 ? " (along with $investmentsCount linked investment record(s))" : '') . '.');
    }

    public function storeProject(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'category' => 'required|string',
            'target_amount' => 'required|numeric|min:1',
            'minimum_investment' => 'required|numeric|min:1',
            'expected_return_percentage' => 'required|numeric|min:0|max:1000',
            'investment_duration_months' => 'required|integer|min:1',
            'rating' => 'nullable|numeric|min:0|max:5',
            'image_url' => 'nullable|string',
            'description' => 'nullable|string',
            'document' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'status' => 'nullable|string|in:active,completed,closed',
        ]);

        $documentPath = null;
        if ($request->hasFile('document')) {
            $documentPath = $request->file('document')->store('project-documents', 'public');
        }

        Project::create([
            'title' => $request->title,
            'location' => $request->location,
            'category' => $request->category,
            'image_url' => $request->image_url ?: 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=1000&auto=format&fit=crop',
            'target_amount' => $request->target_amount,
            'minimum_investment' => $request->minimum_investment,
            'expected_return_percentage' => $request->expected_return_percentage,
            'investment_duration_months' => $request->investment_duration_months,
            'rating' => $request->rating ?? 0.00,
            'description' => $request->description,
            'document_path' => $documentPath,
            'status' => $request->status ?: 'active',
        ]);

        return redirect()->route('admin.dashboard', ['tab' => 'projects'])->with('success', 'New investment project created successfully!');
    }

    public function editProject($id)
    {
        $project = Project::findOrFail($id);

        return view('admin.project-edit', compact('project'));
    }

    public function updateProject(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'category' => 'required|string',
            'target_amount' => 'required|numeric|min:1',
            'minimum_investment' => 'required|numeric|min:1',
            'expected_return_percentage' => 'required|numeric|min:0|max:1000',
            'investment_duration_months' => 'required|integer|min:1',
            'rating' => 'nullable|numeric|min:0|max:5',
            'image_url' => 'nullable|string',
            'description' => 'nullable|string',
            'document' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'status' => 'nullable|string|in:active,completed,closed',
        ]);

        $project = Project::findOrFail($id);
        $project->title = $request->title;
        $project->location = $request->location;
        $project->category = $request->category;
        $project->image_url = $request->image_url;
        $project->target_amount = $request->target_amount;
        $project->minimum_investment = $request->minimum_investment;
        $project->expected_return_percentage = $request->expected_return_percentage;
        $project->investment_duration_months = $request->investment_duration_months;
        $project->rating = $request->rating ?? 0.00;
        $project->description = $request->description;
        $project->status = $request->status ?: $project->status;

        if ($request->hasFile('document')) {
            if ($project->document_path) {
                Storage::disk('public')->delete($project->document_path);
            }
            $project->document_path = $request->file('document')->store('project-documents', 'public');
        }

        $project->save();

        return redirect()->route('admin.dashboard', ['tab' => 'projects'])->with('success', 'Project "' . $project->title . '" updated successfully.');
    }

    public function deleteProject($id)
    {
        $project = Project::findOrFail($id);

        if ($project->document_path) {
            Storage::disk('public')->delete($project->document_path);
        }

        $project->delete();

        return redirect()->back()->with('success', 'Project "' . $project->title . '" deleted.');
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

    public function impersonate($id)
    {
        $targetUser = User::where('role', 'user')->findOrFail($id);

        session([
            'admin_original_id' => Auth::id(),
            'impersonating' => true,
        ]);

        Auth::login($targetUser);
        $request = request();
        $request->session()->regenerateToken();

        return redirect()->route('dashboard')->with('success', 'You are now viewing the platform as ' . $targetUser->name . '.');
    }

    public function stopImpersonation()
    {
        $adminId = session('admin_original_id');

        if ($adminId) {
            $admin = User::find($adminId);

            session()->forget(['admin_original_id', 'impersonating']);

            if ($admin) {
                Auth::login($admin);
                request()->session()->regenerateToken();

                return redirect()->route('admin.dashboard')->with('success', 'You have returned to the admin panel.');
            }
        }

        return redirect()->route('login');
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

    public function approveCard($id)
    {
        $card = Card::with('user')->findOrFail($id);

        if ($card->status === 'approved') {
            return redirect()->back()->with('error', 'This Crypto Card is already approved.');
        }

        $card->status = 'approved';
        $card->card_brand = (rand(0, 1) === 0) ? 'Visa' : 'Mastercard';
        $card->card_number = $this->generateCardNumber();
        $card->expiry_month = now()->addYears(3)->format('m');
        $card->expiry_year = now()->addYears(3)->format('y');
        $card->cvv = str_pad((string) rand(0, 999), 3, '0', STR_PAD_LEFT);
        $card->cardholder_name = $card->cardholder_name ?: $card->user->name;
        $card->approved_at = now();
        $card->save();

        Mail::to($card->user->email)->send(new CardApprovedMail($card));

        return redirect()->back()->with('success', 'Crypto Card approved for ' . $card->user->name . '! Card details generated and emailed to the user.');
    }

    public function rejectCard(Request $request, $id)
    {
        $request->validate(['reason' => 'required|string|max:1000']);

        $card = Card::with('user')->findOrFail($id);

        if ($card->status === 'approved') {
            return redirect()->back()->with('error', 'An approved Crypto Card cannot be rejected.');
        }

        $card->status = 'rejected';
        $card->rejection_reason = $request->reason;
        $card->save();

        Mail::to($card->user->email)->send(new CardRejectedMail($card, $request->reason));

        return redirect()->back()->with('success', 'Crypto Card application rejected for ' . $card->user->name . '. The user has been notified.');
    }

    protected function generateCardNumber(): string
    {
        // Visa cards start with 4; generate a valid 16-digit number (Luhn-checksummed)
        $number = '4' . str_pad((string) rand(0, 999999999999999), 15, '0', STR_PAD_LEFT);
        $digits = array_map('intval', str_split($number));
        $sum = 0;
        for ($i = 0; $i < 15; $i++) {
            $d = $digits[$i];
            if ($i % 2 === 0) {
                $d *= 2;
                if ($d > 9) {
                    $d -= 9;
                }
            }
            $sum += $d;
        }
        $check = (10 - ($sum % 10)) % 10;

        return $number . $check;
    }
}
