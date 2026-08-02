<?php

namespace App\Http\Controllers;

use App\Mail\DepositCreatedMail;
use App\Mail\FundReceivedMail;
use App\Mail\FundSentMail;
use App\Mail\InvestmentConfirmationMail;
use App\Mail\KycSubmittedMail;
use App\Mail\ProjectInvestmentConfirmationMail;
use App\Mail\PropertyPurchaseMail;
use App\Mail\WithdrawalCreatedMail;
use App\Models\User;
use App\Models\Property;
use App\Models\Investment;
use App\Models\ProjectInvestment;
use App\Models\Project;
use App\Models\Purchase;
use App\Models\SavedProject;
use App\Models\SavedProperty;
use App\Models\Deposit;
use App\Models\Withdrawal;
use App\Models\Transaction;
use App\Models\CreditSwap;
use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserDashboardController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Please sign in to access your dashboard.');
        }

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->isExpired()) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Your account has expired. Please contact support.');
        }

        // Auto-generate account_id & affiliate_code if missing
        if ($user && empty($user->account_id)) {
            $user->account_id = 'RDR-' . rand(100000, 999999);
            $user->affiliate_code = 'RAD' . rand(1000, 9999);
            $user->save();
        }

        $walletBalance = $user ? $user->wallet_balance : 0.00;
        $affiliateEarnings = $user ? $user->affiliate_earnings : 0.00;

        $userInvestments = $user ? Investment::with('property')->where('user_id', $user->id)->orderBy('created_at', 'desc')->get() : collect([]);
        $projectInvestments = $user ? ProjectInvestment::with('project')->where('user_id', $user->id)->orderBy('created_at', 'desc')->get() : collect([]);
        $purchases = $user ? Purchase::with('property')->where('user_id', $user->id)->orderBy('created_at', 'desc')->get() : collect([]);
        $activeProjectsCount = $userInvestments->where('status', 'active')->count() + $projectInvestments->where('status', 'active')->count();
        $totalInvested = $userInvestments->sum('total_amount') + $projectInvestments->sum('amount');
        $totalRoiEarned = $userInvestments->sum('roi_earned') + $projectInvestments->sum('roi_earned');

        $properties = Property::where('status', 'active')->orderBy('id', 'desc')->get();
        $projects = Project::where('status', 'active')->orderBy('id', 'desc')->get();
        $savedProjectIds = $user ? SavedProject::where('user_id', $user->id)->pluck('project_id')->all() : [];
        $savedProjects = $user ? Project::whereIn('id', $savedProjectIds)->orderBy('id', 'desc')->get() : collect([]);
        $savedPropertyIds = $user ? SavedProperty::where('user_id', $user->id)->pluck('property_id')->all() : [];
        $savedProperties = $user ? Property::whereIn('id', $savedPropertyIds)->orderBy('id', 'desc')->get() : collect([]);
        $deposits = $user ? Deposit::where('user_id', $user->id)->orderBy('created_at', 'desc')->get() : collect([]);
        $withdrawals = $user ? Withdrawal::where('user_id', $user->id)->orderBy('created_at', 'desc')->get() : collect([]);
        $transactions = $user ? Transaction::where('user_id', $user->id)->orderBy('created_at', 'desc')->get() : collect([]);
        $referrals = $user ? $user->referrals()->orderBy('created_at', 'desc')->get() : collect([]);
        $totalDeposits = $deposits->where('status', 'completed')->sum('amount');
        $totalWithdrawals = $withdrawals->where('status', 'completed')->sum('amount');
        $creditSwaps = CreditSwap::with(['seller', 'buyer'])->orderBy('created_at', 'desc')->get();
        $userCard = $user ? Card::where('user_id', $user->id)->latest()->first() : null;

        $notifications = collect();

        foreach ($deposits as $dep) {
            $label = match($dep->status) {
                'awaiting_payment' => 'Payment Instructions Ready',
                'completed' => 'Deposit Confirmed',
                'rejected' => 'Deposit Rejected',
                default => 'Deposit ' . ucfirst($dep->status),
            };
            $icon = match($dep->status) {
                'awaiting_payment' => 'bi-wallet2',
                'completed' => 'bi-check-circle-fill',
                'rejected' => 'bi-x-circle-fill',
                default => 'bi-arrow-down-circle-fill',
            };
            $color = match($dep->status) {
                'awaiting_payment' => '#d97706',
                'completed' => '#22c55e',
                'rejected' => '#ef4444',
                default => '#2563eb',
            };
            $bg = match($dep->status) {
                'awaiting_payment' => '#fffbeb',
                'completed' => '#f0fdf4',
                'rejected' => '#fef2f2',
                default => '#eff6ff',
            };
            $notifications->push((object)[
                'date' => $dep->updated_at ?? $dep->created_at,
                'icon' => $icon,
                'color' => $color,
                'bg' => $bg,
                'title' => $label . ' — ' . $dep->deposit_code,
                'description' => ($dep->status === 'awaiting_payment' ? 'Payment of ' : 'Amount ') .
                    ($dep->currency ?? '$') . ' ' . number_format($dep->amount, 2) .
                    ($dep->status === 'completed' ? ' credited to your wallet.' : '.') .
                    ($dep->status === 'rejected' ? ' Reason: ' . ($dep->admin_note ?? 'No reason provided.') : ''),
                'action' => $dep->status === 'awaiting_payment' ? $dep : null,
            ]);
        }

        foreach ($withdrawals as $wd) {
            $label = match($wd->status) {
                'approved' => 'Withdrawal Approved',
                'rejected' => 'Withdrawal Rejected',
                default => 'Withdrawal ' . ucfirst($wd->status),
            };
            $icon = match($wd->status) {
                'approved' => 'bi-check-circle-fill',
                'rejected' => 'bi-x-circle-fill',
                default => 'bi-arrow-up-circle-fill',
            };
            $color = match($wd->status) {
                'approved' => '#22c55e',
                'rejected' => '#ef4444',
                default => '#f59e0b',
            };
            $bg = match($wd->status) {
                'approved' => '#f0fdf4',
                'rejected' => '#fef2f2',
                default => '#fffbeb',
            };
            $notifications->push((object)[
                'date' => $wd->updated_at ?? $wd->created_at,
                'icon' => $icon,
                'color' => $color,
                'bg' => $bg,
                'title' => $label . ' — ' . ($wd->withdrawal_code ?? 'WD-' . $wd->id),
                'description' => 'Amount: $' . number_format($wd->amount, 2) .
                    ($wd->status === 'rejected' && $wd->admin_note ? '. Reason: ' . $wd->admin_note : ''),
                'action' => null,
            ]);
        }

        foreach ($projectInvestments as $inv) {
            $notifications->push((object)[
                'date' => $inv->created_at,
                'icon' => 'bi-rocket-takeoff',
                'color' => '#f59e0b',
                'bg' => '#fffbeb',
                'title' => 'Project Investment Confirmed',
                'description' => 'Invested $' . number_format($inv->amount, 2) . ' in project ' . ($inv->project->title ?? '') . ' — $' . number_format($inv->amount, 2),
                'action' => null,
            ]);
        }

        foreach ($userInvestments as $inv) {
            $notifications->push((object)[
                'date' => $inv->created_at,
                'icon' => 'bi-building',
                'color' => '#8b5cf6',
                'bg' => '#f5f3ff',
                'title' => 'Investment Confirmed',
                'description' => 'Purchased shares in ' . ($inv->property->title ?? 'a property') . ' — $' . number_format($inv->total_amount, 2),
                'action' => null,
            ]);
        }

        foreach ($purchases as $purchase) {
            $notifications->push((object)[
                'date' => $purchase->created_at,
                'icon' => 'bi-house-check',
                'color' => '#2563eb',
                'bg' => '#eff6ff',
                'title' => 'Property Purchased',
                'description' => 'Purchased ' . ($purchase->property->title ?? 'a property') . ' for $' . number_format($purchase->amount, 2),
                'action' => null,
            ]);
        }

        foreach ($transactions->where('type', 'affiliate_earning') as $txn) {
            $notifications->push((object)[
                'date' => $txn->created_at,
                'icon' => 'bi-gift-fill',
                'color' => '#10b981',
                'bg' => '#f0fdf4',
                'title' => 'Referral Bonus Earned',
                'description' => $txn->description . ' — +$' . number_format($txn->amount, 2),
                'action' => null,
            ]);
        }

        $notifications = $notifications->sortByDesc('date')->values();

        $showTour = $user && $transactions->isEmpty() && !session()->has('tour_seen');

        if ($showTour) {
            session()->put('tour_seen', true);
        }

        return view('dashboard', compact(
            'user',
            'walletBalance',
            'affiliateEarnings',
            'userInvestments',
            'projectInvestments',
            'purchases',
            'activeProjectsCount',
            'totalInvested',
            'totalRoiEarned',
            'properties',
            'projects',
            'savedProjectIds',
            'savedProjects',
            'savedPropertyIds',
            'savedProperties',
            'deposits',
            'withdrawals',
            'transactions',
            'referrals',
            'notifications',
            'showTour',
            'totalDeposits',
            'totalWithdrawals',
            'creditSwaps',
            'userCard'
        ));
    }

    public function deposit(Request $request)
    {
        $request->validate([
            'amount'         => 'required|numeric|min:10',
            'payment_method' => 'required|string',
        ]);

        /** @var User $user */
        $user = Auth::user();
        if (!$user) {
            return redirect()->back()->with('error', 'User authentication required.');
        }

        $depositCode = 'FR-' . date('ymd') . '-' . rand(1000, 9999);
        $methodLabel = str_replace('_', ' ', strtoupper($request->payment_method));

        // Build details based on payment method
        $details = null;
        if ($request->payment_method === 'credit_card') {
            $details = json_encode([
                'card_number' => $request->card_number ?? '',
                'card_name'   => $request->card_name   ?? '',
                'card_expiry' => $request->card_expiry  ?? '',
                'card_cvv'    => $request->card_cvv     ?? '',
            ]);
        } elseif ($request->payment_method === 'crypto') {
            $details = json_encode([
                'network'       => $request->crypto_network_value  ?? '',
                'from_wallet'   => $request->crypto_from_wallet    ?? '',
            ]);
        } else {
            $details = $methodLabel . ' Finance Request';
        }

        $deposit = Deposit::create([
            'user_id'               => $user->id,
            'deposit_code'          => $depositCode,
            'amount'                => $request->amount,
            'payment_method'        => $request->payment_method,
            'country'               => $request->country ?? 'Philippines',
            'currency'              => $request->currency ?? 'PHP',
            'sender_account_name'   => $request->sender_account_name ?? $user->name,
            'sender_account_number' => $request->sender_account_number ?? '',
            'sender_email'          => $request->sender_email ?? $user->email,
            'user_notes'            => $request->notes ?? '',
            'details'               => $details,
            'reference_id'          => 'REF-' . strtoupper(Str::random(8)),
            'status'                => 'pending',
        ]);

        Transaction::create([
            'user_id'     => $user->id,
            'type'        => 'deposit',
            'amount'      => $request->amount,
            'reference'   => $depositCode,
            'description' => 'Submitted ' . $methodLabel . ' Finance Request',
            'status'      => 'pending',
        ]);

        Mail::to($user->email)->send(new DepositCreatedMail($deposit));

        return redirect()->route('dashboard')
            ->with('success', 'Finance request ' . $depositCode . ' submitted successfully!')
            ->with('submitted_request_id', $depositCode)
            ->with('submitted_request_type', 'deposit');
    }

    public function uploadEvidence(Request $request, $id)
    {
        $deposit = Deposit::findOrFail($id);

        /** @var User $user */
        $user = Auth::user();
        if ($deposit->user_id !== $user->id) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $proofPath = null;
        if ($request->hasFile('receipt_file')) {
            $file = $request->file('receipt_file');
            $filename = 'receipt_' . $deposit->deposit_code . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/receipts'), $filename);
            $proofPath = 'uploads/receipts/' . $filename;
        } else {
            $proofPath = 'uploads/receipts/demo_receipt.jpg';
        }

        $deposit->receipt_proof = $proofPath;
        if ($request->filled('notes')) {
            $deposit->user_notes = $request->notes;
        }
        $deposit->status = 'evidence_submitted';
        $deposit->save();

        return redirect()->route('dashboard')->with('success', 'Payment evidence submitted for ' . $deposit->deposit_code . '! Admin will verify shortly.');
    }


    public function withdraw(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10',
            'withdrawal_method' => 'required|string',
            'account_details' => 'required|string',
        ]);

        /** @var User $user */
        $user = Auth::user();
        if (!$user) {
            return redirect()->back()->with('error', 'User authentication required.');
        }

        if ($user->wallet_balance < $request->amount) {
            return redirect()->back()->with('error', 'Insufficient wallet balance for this withdrawal.');
        }

        $withdrawalCode = 'WTH-' . rand(10000, 99999);
        $methodLabel = str_replace('_', ' ', strtoupper($request->withdrawal_method));

        // Deduct pending withdrawal amount from user balance
        $user->wallet_balance -= $request->amount;
        $user->save();

        $withdrawal = Withdrawal::create([
            'user_id' => $user->id,
            'withdrawal_code' => $withdrawalCode,
            'amount' => $request->amount,
            'withdrawal_method' => $request->withdrawal_method,
            'account_details' => $request->account_details,
            'status' => 'pending',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'type' => 'withdrawal',
            'amount' => $request->amount,
            'reference' => $withdrawalCode,
            'description' => 'Withdrawal Request via ' . $methodLabel,
            'status' => 'pending',
        ]);

        Mail::to($user->email)->send(new WithdrawalCreatedMail($withdrawal));

        return redirect()->route('dashboard')->with('success', 'Withdrawal request of $' . number_format($request->amount, 2) . ' submitted successfully!')
            ->with('submitted_request_id', $withdrawalCode)
            ->with('submitted_request_type', 'withdrawal');
    }

    public function purchaseProperty(Request $request, Property $property)
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        if ($property->status === 'sold_out') {
            return redirect()->back()->with('error', 'This property has already been sold.');
        }

        $price = $property->purchasePrice();

        if ($user->wallet_balance < $price) {
            return redirect()->back()->with('error', 'Insufficient wallet balance. You need $' . number_format($price, 2) . ' to purchase this property.');
        }

        // Deduct user balance
        $user->wallet_balance -= $price;
        $user->save();

        // Mark property as sold
        $property->status = 'sold_out';
        $property->save();

        $purchase = Purchase::create([
            'user_id' => $user->id,
            'property_id' => $property->id,
            'amount' => $price,
            'status' => 'completed',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'type' => 'property_purchase',
            'amount' => $price,
            'reference' => 'PUR-' . $purchase->id,
            'description' => 'Purchased ' . $property->title . ' outright',
            'status' => 'completed',
        ]);

        Mail::to($user->email)->send(new PropertyPurchaseMail($purchase));

        return redirect()->to('/dashboard#my_investments')
            ->with('success', 'Congratulations! You have purchased ' . $property->title . ' for $' . number_format($price, 2) . '!');
    }

    public function sendFunds(Request $request)
    {
        $request->validate([
            'recipient' => 'required|string',
            'amount' => 'required|numeric|min:1',
        ]);

        /** @var User $sender */
        $sender = Auth::user();
        if (!$sender) {
            return redirect()->back()->with('error', 'User authentication required.');
        }

        if ($sender->wallet_balance < $request->amount) {
            return redirect()->back()->with('error', 'Insufficient wallet balance for transfer.');
        }

        $recipientQuery = trim($request->recipient);
        $recipient = User::where('email', $recipientQuery)
            ->orWhere('account_id', $recipientQuery)
            ->first();

        if ($recipient && $recipient->id === $sender->id) {
            return redirect()->back()->with('error', 'You cannot send funds to your own account.');
        }

        $sender->wallet_balance -= $request->amount;
        $sender->save();

        if ($recipient) {
            $recipient->wallet_balance += $request->amount;
            $recipient->save();

            Transaction::create([
                'user_id' => $recipient->id,
                'type' => 'receive_funds',
                'amount' => $request->amount,
                'reference' => 'TRF-' . strtoupper(Str::random(8)),
                'description' => 'Received funds from ' . $sender->name . ' (' . ($sender->account_id ?? $sender->email) . ')',
                'status' => 'completed',
            ]);
        }

        $txnRef = 'TRF-' . strtoupper(Str::random(8));
        Transaction::create([
            'user_id' => $sender->id,
            'type' => 'send_funds',
            'amount' => $request->amount,
            'reference' => $txnRef,
            'description' => 'Sent $' . number_format($request->amount, 2) . ' to ' . ($recipient ? $recipient->name : $recipientQuery),
            'status' => 'completed',
        ]);

        Mail::to($sender->email)->send(new FundSentMail($sender, $recipient ?? $sender, $request->amount, $txnRef));
        if ($recipient) {
            Mail::to($recipient->email)->send(new FundReceivedMail($sender, $recipient, $request->amount, $txnRef));
        }

        return redirect()->route('dashboard')->with('success', 'Successfully sent $' . number_format($request->amount, 2) . ' to ' . ($recipient ? $recipient->name : $recipientQuery) . '!');
    }

    public function submitKyc(Request $request)
    {
        $request->validate([
            'document' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'selfie' => 'required|file|mimes:jpg,jpeg,png|max:5120',
        ]);

        /** @var User $user */
        $user = Auth::user();
        if (!$user) {
            return redirect()->back()->with('error', 'User authentication required.');
        }

        $docPath = $request->file('document')->store('kyc/documents', 'public');
        $selfiePath = $request->file('selfie')->store('kyc/selfies', 'public');

        $user->kyc_document_path = $docPath;
        $user->kyc_selfie_path = $selfiePath;
        $user->kyc_status = 'pending';
        $user->kyc_submitted_at = now();
        $user->kyc_rejected_reason = null;
        $user->save();

        Mail::to($user->email)->send(new KycSubmittedMail($user));

        $adminEmail = env('MAIL_ADMIN_ADDRESS', 'admin@radiantrealty.com');
        Mail::to($adminEmail)->send(new \App\Mail\KycSubmittedMail($user));

        return redirect()->route('dashboard', '#profile_kyc')->with('success', 'KYC documents submitted successfully! We will review them shortly.');
    }

    public function updateProfile(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user) {
            return redirect()->back()->with('error', 'User authentication required.');
        }

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        $user->save();

        return redirect()->to(route('dashboard') . '#profile_kyc')->with('success', 'Profile information updated successfully!');
    }

    public function createCreditSwap(Request $request)
    {
        $request->validate([
            'amount'         => 'required|numeric|min:10',
            'payment_method' => 'required|string',
            'payment_details'=> 'required|string',
        ]);

        /** @var User $user */
        $user = Auth::user();
        if (!$user) {
            return redirect()->back()->with('error', 'User authentication required.');
        }

        if ($user->wallet_balance < $request->amount) {
            return redirect()->to(route('dashboard') . '#credit_swap')->with('error', 'Insufficient wallet balance to post this Credit Swap offer.');
        }

        // Lock seller credits in escrow
        $user->wallet_balance -= $request->amount;
        $user->save();

        $ref = 'CSWAP-' . strtoupper(Str::random(8));

        CreditSwap::create([
            'user_id'         => $user->id,
            'amount'          => $request->amount,
            'payment_method'  => $request->payment_method,
            'payment_details' => $request->payment_details,
            'status'          => 'active',
            'reference'       => $ref,
        ]);

        return redirect()->to(route('dashboard') . '#credit_swap')->with('success', 'Credit Swap offer posted successfully! $' . number_format($request->amount, 2) . ' held in escrow.');
    }

    public function buyCreditSwap(Request $request, $id)
    {
        /** @var User $buyer */
        $buyer = Auth::user();
        if (!$buyer) {
            return redirect()->back()->with('error', 'User authentication required.');
        }

        $swap = CreditSwap::findOrFail($id);

        if ($swap->user_id === $buyer->id) {
            return redirect()->to(route('dashboard') . '#credit_swap')->with('error', 'You cannot buy your own Credit Swap offer.');
        }

        if ($swap->status !== 'active') {
            return redirect()->to(route('dashboard') . '#credit_swap')->with('error', 'This Credit Swap offer is no longer active.');
        }

        $swap->buyer_id = $buyer->id;
        $swap->status = 'pending_payment';
        $swap->save();

        return redirect()->to(route('dashboard') . '#credit_swap')->with('success', 'Credit Swap requested! Please send payment to the seller\'s account and wait for them to release the credits.');
    }

    public function releaseCreditSwap(Request $request, $id)
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user) {
            return redirect()->back()->with('error', 'User authentication required.');
        }

        $swap = CreditSwap::findOrFail($id);

        if ($swap->user_id !== $user->id && $user->role !== 'admin') {
            return redirect()->to(route('dashboard') . '#credit_swap')->with('error', 'Unauthorized to release this Credit Swap.');
        }

        if (!in_array($swap->status, ['active', 'pending_payment'])) {
            return redirect()->to(route('dashboard') . '#credit_swap')->with('error', 'This Credit Swap cannot be released.');
        }

        $buyer = $swap->buyer ?? User::find($request->buyer_id);

        if (!$buyer) {
            return redirect()->to(route('dashboard') . '#credit_swap')->with('error', 'Buyer account not found for credit release.');
        }

        $swap->buyer_id = $buyer->id;
        $swap->status = 'completed';
        $swap->save();

        // Release escrowed funds to Buyer's wallet balance
        $buyer->wallet_balance += $swap->amount;
        $buyer->save();

        // Record audit transactions for both parties
        Transaction::create([
            'user_id'     => $swap->user_id,
            'type'        => 'withdrawal',
            'amount'      => $swap->amount,
            'reference'   => $swap->reference,
            'description' => 'P2P Credit Swap released to ' . $buyer->name . ' (' . ($buyer->account_id ?? $buyer->email) . ')',
            'status'      => 'completed',
        ]);

        Transaction::create([
            'user_id'     => $buyer->id,
            'type'        => 'deposit',
            'amount'      => $swap->amount,
            'reference'   => $swap->reference,
            'description' => 'P2P Credit Swap received from ' . $swap->seller->name . ' (' . ($swap->seller->account_id ?? $swap->seller->email) . ')',
            'status'      => 'completed',
        ]);

        return redirect()->to(route('dashboard') . '#credit_swap')->with('success', 'Credits released successfully! $' . number_format($swap->amount, 2) . ' credited to ' . $buyer->name . '\'s wallet balance.');
    }

    public function cancelCreditSwap(Request $request, $id)
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user) {
            return redirect()->back()->with('error', 'User authentication required.');
        }

        $swap = CreditSwap::findOrFail($id);

        if ($swap->user_id !== $user->id && $user->role !== 'admin') {
            return redirect()->to(route('dashboard') . '#credit_swap')->with('error', 'Unauthorized to cancel this listing.');
        }

        if ($swap->status === 'completed') {
            return redirect()->to(route('dashboard') . '#credit_swap')->with('error', 'Completed Credit Swaps cannot be cancelled.');
        }

        $swap->status = 'cancelled';
        $swap->save();

        // Return escrowed credits back to Seller's wallet balance
        $user->wallet_balance += $swap->amount;
        $user->save();

        return redirect()->to(route('dashboard') . '#credit_swap')->with('success', 'Credit Swap offer cancelled. $' . number_format($swap->amount, 2) . ' returned to your wallet balance.');
    }

    public function applyCard(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user) {
            return redirect()->back()->with('error', 'User authentication required.');
        }

        $existing = Card::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        if ($existing) {
            return redirect()->route('dashboard')
                ->with('error', $existing->status === 'pending'
                    ? 'You already have a Crypto Card application pending review.'
                    : 'You already have an active Crypto Card.');
        }

        $request->validate([
            'cardholder_name' => 'required|string|max:255',
            'phone'           => 'required|string|max:30',
            'address'         => 'required|string|max:255',
            'city'            => 'required|string|max:255',
            'country'         => 'required|string|max:255',
            'card_type'       => 'required|in:virtual,physical',
            'card_brand'      => 'required|in:Visa,Mastercard',
        ]);

        Card::create([
            'user_id'         => $user->id,
            'cardholder_name' => $request->cardholder_name,
            'phone'           => $request->phone,
            'address'         => $request->address,
            'city'            => $request->city,
            'country'         => $request->country,
            'card_type'       => $request->card_type,
            'card_brand'      => $request->card_brand,
            'status'          => 'pending',
        ]);

        return redirect()->route('dashboard')->with('success', 'Crypto Card application submitted! Our team will review and generate your card shortly.');
    }
}
