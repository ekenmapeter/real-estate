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
        $preferredCurrency = $user && $user->preferred_currency ? strtoupper($user->preferred_currency) : 'USD';

        $userInvestments = $user ? Investment::with('property')->where('user_id', $user->id)->orderBy('created_at', 'desc')->get() : collect([]);
        $projectInvestments = $user ? ProjectInvestment::with('project')->where('user_id', $user->id)->orderBy('created_at', 'desc')->get() : collect([]);
        $purchases = $user ? Purchase::with('property')->where('user_id', $user->id)->orderBy('created_at', 'desc')->get() : collect([]);
        $activeProjectsCount = $userInvestments->where('status', 'active')->count() + $projectInvestments->where('status', 'active')->count();
        $totalInvested = $userInvestments->sum('total_amount') + $projectInvestments->sum('amount');
        $totalRoiEarned = $userInvestments->sum('roi_earned') + $projectInvestments->sum('roi_earned');

        $properties = Property::where('status', 'published')->orderBy('id', 'desc')->get();
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
                    ($dep->status === 'completed' ? ' credited to your AVC balance.' : '.') .
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
                'description' => 'Amount: ' . format_avc($wd->amount) .
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
                'description' => 'Invested ' . format_avc($inv->amount) . ' in project ' . ($inv->project->title ?? '') . ' — ' . format_avc($inv->amount),
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
                'description' => 'Purchased shares in ' . ($inv->property->title ?? 'a property') . ' — ' . format_avc($inv->total_amount),
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
                'description' => 'Purchased ' . ($purchase->property->title ?? 'a property') . ' for ' . format_avc($purchase->amount),
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
                'description' => $txn->description . ' — +' . format_avc($txn->amount),
                'action' => null,
            ]);
        }

        foreach ($creditSwaps->filter(fn($s) => $user && $s->user_id === $user->id) as $swap) {
            [$label, $icon, $color, $bg] = match ($swap->status) {
                'pending'   => ['Marketplace Listing Pending Approval', 'bi-hourglass-split', '#d97706', '#fffbeb'],
                'active'    => ['Marketplace Listing Approved & Live', 'bi-check-circle-fill', '#16a34a', '#f0fdf4'],
                'in_deal'   => ['Marketplace Deal in Progress', 'bi-arrow-left-right', '#2563eb', '#eff6ff'],
                'paused'    => ['Marketplace Listing Paused', 'bi-pause-circle-fill', '#d97706', '#fffbeb'],
                'rejected'  => ['Marketplace Listing Rejected', 'bi-x-circle-fill', '#dc2626', '#fef2f2'],
                'completed' => ['Marketplace Deal Completed', 'bi-arrow-repeat', '#2563eb', '#eff6ff'],
                default     => ['Marketplace Listing ' . ucfirst($swap->status), 'bi-arrow-repeat', '#64748b', '#f1f5f9'],
            };

            $notifications->push((object)[
                'date' => $swap->updated_at ?? $swap->created_at,
                'icon' => $icon,
                'color' => $color,
                'bg' => $bg,
                'title' => $label . ' — ' . $swap->listingLabel(),
                'description' => match ($swap->status) {
                    'pending' => 'Your ' . strtoupper($swap->offer_type) . ' listing for ' . format_avc($swap->amount) . ' is awaiting admin approval. It will go live once approved.',
                    'active' => 'Your ' . strtoupper($swap->offer_type) . ' listing for ' . format_avc($swap->amount) . ' has been approved and is now live on the marketplace.',
                    'in_deal' => 'A deal has started on your ' . strtoupper($swap->offer_type) . ' listing ' . $swap->listingLabel() . ' (' . format_avc($swap->amount) . '). The finance team is handling it.',
                    'paused' => 'Your listing ' . $swap->listingLabel() . ' has been paused by admin. Contact support for details.',
                    'rejected' => 'Your ' . strtoupper($swap->offer_type) . ' listing for ' . format_avc($swap->amount) . ' was rejected.' . ($swap->admin_note ? ' Reason: ' . $swap->admin_note : '') . ($swap->offer_type === 'sell' ? ' Your escrowed AVC has been refunded.' : ''),
                    'completed' => 'Your ' . strtoupper($swap->offer_type) . ' deal ' . $swap->listingLabel() . ' (' . format_avc($swap->amount) . ') was completed by the finance team.',
                    default => 'Status updated to ' . ucfirst($swap->status) . '.',
                },
                'action' => null,
            ]);
        }

        foreach ($creditSwaps->filter(fn($s) => $user && ($s->buyer_id === $user->id || $s->seller_id === $user->id) && $s->user_id !== $user->id) as $swap) {
            $isYourBuy = $swap->buyer_id === $user->id;
            if ($swap->status === 'in_deal') {
                $notifications->push((object)[
                    'date' => $swap->updated_at ?? $swap->created_at,
                    'icon' => 'bi-arrow-left-right',
                    'color' => '#2563eb',
                    'bg' => '#eff6ff',
                    'title' => 'Marketplace Deal Started — ' . $swap->listingLabel(),
                    'description' => $isYourBuy
                        ? 'You started a deal to buy ' . format_avc($swap->amount) . '. The finance team will contact you via Telegram to complete it.'
                        : 'You started a deal to sell your AVC for ' . format_avc($swap->amount) . '. ' . format_avc($swap->amount) . ' is held in escrow until the finance team completes the deal.',
                    'action' => null,
                ]);
            } elseif ($swap->status === 'completed') {
                $notifications->push((object)[
                    'date' => $swap->updated_at ?? $swap->created_at,
                    'icon' => 'bi-check-circle-fill',
                    'color' => '#16a34a',
                    'bg' => '#f0fdf4',
                    'title' => 'Marketplace Deal Completed — ' . $swap->listingLabel(),
                    'description' => $isYourBuy
                        ? 'Your purchase of ' . format_avc($swap->amount) . ' is complete. The credits have been added to your AVC balance.'
                        : 'Your sale of ' . format_avc($swap->amount) . ' is complete. Thank you for using the marketplace.',
                    'action' => null,
                ]);
            } elseif ($swap->status === 'paused') {
                $notifications->push((object)[
                    'date' => $swap->updated_at ?? $swap->created_at,
                    'icon' => 'bi-pause-circle-fill',
                    'color' => '#d97706',
                    'bg' => '#fffbeb',
                    'title' => 'Marketplace Deal Paused — ' . $swap->listingLabel(),
                    'description' => 'The deal on ' . $swap->listingLabel() . ' has been paused by the finance team. Contact support on Telegram for updates.',
                    'action' => null,
                ]);
            }
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
            'userCard',
            'preferredCurrency'
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
            return redirect()->back()->with('error', 'Insufficient AVC balance for this withdrawal.');
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

        return redirect()->route('dashboard')->with('success', 'Withdrawal request of ' . format_avc($request->amount) . ' submitted successfully!')
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

        if ($property->status === 'sold' || $property->status === 'rented') {
            return redirect()->back()->with('error', 'This property has already been sold.');
        }

        $price = $property->purchasePrice();

        if ($user->wallet_balance < $price) {
            return redirect()->back()->with('error', 'Insufficient AVC balance. You need ' . format_avc($price) . ' to purchase this property.');
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

        try {
            $documents = app(\App\Services\DocumentService::class);
            $documents->generate('property_contract', $purchase, $user, [
                'metadata' => ['related_label' => $property->title . ' (' . $property->ref() . ')'],
            ]);
            $documents->generate('property_receipt', $purchase, $user, [
                'metadata' => ['related_label' => $property->title . ' (' . $property->ref() . ')'],
            ]);
        } catch (\Throwable $e) {
            report($e);
        }

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
            ->with('success', 'Congratulations! You have purchased ' . $property->title . ' for ' . format_avc($price) . '!');
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
            return redirect()->back()->with('error', 'Insufficient AVC balance for transfer.');
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
                'description' => 'Received AVC from ' . $sender->name . ' (' . ($sender->account_id ?? $sender->email) . ')',
                'status' => 'completed',
            ]);
        }

        $txnRef = 'TRF-' . strtoupper(Str::random(8));
        Transaction::create([
            'user_id' => $sender->id,
            'type' => 'send_funds',
            'amount' => $request->amount,
            'reference' => $txnRef,
            'description' => 'Sent ' . format_avc($request->amount) . ' to ' . ($recipient ? $recipient->name : $recipientQuery),
            'status' => 'completed',
        ]);

        Mail::to($sender->email)->send(new FundSentMail($sender, $recipient ?? $sender, $request->amount, $txnRef));
        if ($recipient) {
            Mail::to($recipient->email)->send(new FundReceivedMail($sender, $recipient, $request->amount, $txnRef));
        }

        return redirect()->to(route('dashboard') . '#transfer')->with('success', 'Successfully sent ' . format_avc($request->amount) . ' to ' . ($recipient ? $recipient->name : $recipientQuery) . '!');
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

        try {
            $documents = app(\App\Services\DocumentService::class);
            $documents->registerExternal('identity_report', $user, 'Identity Document', $docPath, [
                'related_label' => 'KYC Identity Document',
                'document_label' => 'Identity Document',
            ], 'pending');
            $documents->registerExternal('identity_report', $user, 'Identity Selfie', $selfiePath, [
                'related_label' => 'KYC Identity Selfie',
                'document_label' => 'Identity Selfie',
            ], 'pending');
        } catch (\Throwable $e) {
            report($e);
        }

        Mail::to($user->email)->send(new KycSubmittedMail($user));

        $adminEmail = config('mail.admin_email');
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
            'name'              => 'required|string|max:255',
            'email'             => 'required|email|max:255|unique:users,email,' . $user->id,
            'password'          => 'nullable|string|min:8|confirmed',
            'preferred_currency'=> 'nullable|string|in:USD,EUR,GBP,PHP,NGN,AED,SGD,CAD,AUD',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('preferred_currency')) {
            $user->preferred_currency = strtoupper($request->preferred_currency);
        }

        if ($request->filled('password')) {
            $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        $user->save();

        return redirect()->to(route('dashboard') . '#profile_kyc')->with('success', 'Profile information updated successfully!');
    }

    public function createCreditSwap(Request $request)
    {
        $request->validate([
            'amount'          => 'required|numeric|min:10',
            'offer_type'      => 'required|in:buy,sell',
            'country'         => 'required|string|max:255',
            'payment_method'  => 'required|string',
            'payment_details' => 'nullable|string',
            'notes'           => 'nullable|string|max:1000',
        ]);

        /** @var User $user */
        $user = Auth::user();
        if (!$user) {
            return redirect()->back()->with('error', 'User authentication required.');
        }

        $isSell = $request->offer_type === 'sell';

        if ($isSell && $user->wallet_balance < $request->amount) {
            return redirect()->to(route('marketplace'))->with('error', 'Insufficient AVC balance to post this marketplace listing.');
        }

        if ($isSell) {
            // Lock seller AVC in escrow
            $user->wallet_balance -= $request->amount;
            $user->save();
        }

        $ref = 'CSWAP-' . strtoupper(Str::random(8));

        $swap = CreditSwap::create([
            'user_id'         => $user->id,
            'offer_type'      => $request->offer_type,
            'country'         => $request->country,
            'amount'          => $request->amount,
            'payment_method'  => $request->payment_method,
            'payment_details' => $request->payment_details,
            'notes'           => $request->notes,
            'status'          => 'pending',
            'reference'       => $ref,
        ]);

        $swap->appendLog('Listing submitted for admin review.', $user->name);
        $swap->save();

        $message = $isSell
            ? 'Sell listing submitted for admin review! ' . format_avc($request->amount) . ' held in escrow. You will be notified once it is approved.'
            : 'Buy listing submitted for admin review! You will be notified once it is approved.';

        return redirect()->to(route('marketplace'))->with('success', $message);
    }

    public function dealCreditSwap(Request $request, $id)
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $swap = CreditSwap::with(['seller', 'buyer', 'responder'])->findOrFail($id);

        if ($swap->status !== 'active') {
            return redirect()->to(route('marketplace'))->with('error', 'This listing is no longer available on the marketplace.');
        }

        if ($swap->user_id === $user->id) {
            return redirect()->to(route('marketplace'))->with('error', 'You cannot start a deal on your own listing.');
        }

        if ($swap->inDeal()) {
            return redirect()->to(route('marketplace'))->with('error', 'A deal is already in progress for ' . $swap->listingLabel() . '.');
        }

        if ($swap->offer_type === 'sell') {
            // The logged-in user is the buyer on a sell listing
            $swap->buyer_id = $user->id;
        } else {
            // The logged-in user is the seller responding to a buy listing — escrow their AVC
            if ($user->wallet_balance < $swap->amount) {
                return redirect()->to(route('marketplace'))->with('error', 'Insufficient AVC balance to cover ' . $swap->listingLabel() . '.');
            }
            $user->wallet_balance -= $swap->amount;
            $user->save();
            $swap->seller_id = $user->id;
        }

        $swap->status = 'in_deal';
        $swap->appendLog('Deal started by ' . $user->name . '.', $user->name);
        $swap->save();

        $isBuyer = $swap->offer_type === 'sell';
        $message = $isBuyer
            ? 'Hello Finance Team, I\'m interested in Listing #' . $swap->listingLabel() . '. I\'d like to buy ' . format_avc($swap->amount) . ' (' . $swap->payment_method . '). Please guide me through the next steps.'
            : 'Hello Finance Team, I\'d like to sell my AVC for Listing #' . $swap->listingLabel() . ' (' . format_avc($swap->amount) . '). Please guide me through the next steps.';

        return redirect(telegram_url($message));
    }

    public function updateCreditSwap(Request $request, $id)
    {
        $request->validate([
            'amount'          => 'required|numeric|min:10',
            'country'         => 'required|string|max:255',
            'payment_method'  => 'required|string',
            'notes'           => 'nullable|string|max:1000',
        ]);

        /** @var User $user */
        $user = Auth::user();
        $swap = CreditSwap::findOrFail($id);

        if ($swap->user_id !== $user->id) {
            return redirect()->back()->with('error', 'Unauthorized to edit this listing.');
        }

        if (!in_array($swap->status, ['pending', 'active', 'rejected'])) {
            return redirect()->to(route('marketplace'))->with('error', 'This listing cannot be edited while a deal is in progress.');
        }

        $isSell = $swap->offer_type === 'sell';
        $diff = $request->amount - $swap->amount;

        if ($isSell) {
            if ($diff > 0 && $user->wallet_balance < $diff) {
                return redirect()->to(route('marketplace'))->with('error', 'Insufficient AVC balance for the updated amount.');
            }
            if ($diff != 0) {
                $user->wallet_balance -= $diff;
                $user->save();
            }
        }

        $swap->amount = $request->amount;
        $swap->country = $request->country;
        $swap->payment_method = $request->payment_method;
        $swap->notes = $request->notes;
        $swap->status = 'pending';
        $swap->appendLog('Listing edited by ' . $user->name . ' — re-submitted for admin review.', $user->name);
        $swap->save();

        return redirect()->to(route('marketplace'))->with('success', 'Listing updated and re-submitted for admin review.');
    }

    public function repostCreditSwap($id)
    {
        /** @var User $user */
        $user = Auth::user();
        $swap = CreditSwap::findOrFail($id);

        if ($swap->user_id !== $user->id) {
            return redirect()->back()->with('error', 'Unauthorized to repost this listing.');
        }

        if ($swap->offer_type === 'sell' && $user->wallet_balance < $swap->amount) {
            return redirect()->to(route('marketplace'))->with('error', 'Insufficient AVC balance to repost this listing.');
        }

        $isSell = $swap->offer_type === 'sell';
        if ($isSell) {
            $user->wallet_balance -= $swap->amount;
            $user->save();
        }

        $ref = 'CSWAP-' . strtoupper(Str::random(8));

        $newSwap = CreditSwap::create([
            'user_id'         => $user->id,
            'offer_type'      => $swap->offer_type,
            'country'         => $swap->country,
            'amount'          => $swap->amount,
            'payment_method'  => $swap->payment_method,
            'payment_details' => $swap->payment_details,
            'notes'           => $swap->notes,
            'status'          => 'pending',
            'reference'       => $ref,
        ]);

        $newSwap->appendLog('Reposted from ' . $swap->listingLabel() . '. Awaiting admin review.', $user->name);
        $newSwap->save();

        return redirect()->to(route('marketplace'))->with('success', 'Listing reposted! It is now awaiting admin review.');
    }

    public function buyCreditSwap(Request $request, $id)
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user) {
            return redirect()->back()->with('error', 'User authentication required.');
        }

        $swap = CreditSwap::findOrFail($id);

        if ($swap->user_id === $user->id) {
            return redirect()->to(route('dashboard') . '#credit_swap')->with('error', 'You cannot respond to your own AVC Marketplace offer.');
        }

        if ($swap->status !== 'active') {
            return redirect()->to(route('dashboard') . '#credit_swap')->with('error', 'This AVC Marketplace offer is no longer active.');
        }

        if ($swap->offer_type === 'buy') {
            // Buy offer: the responder holds AVC and becomes the seller (escrow their AVC)
            $request->validate([
                'payment_details' => 'required|string',
            ]);

            if ($swap->seller_id) {
                return redirect()->to(route('dashboard') . '#credit_swap')->with('error', 'A seller has already responded to this buy offer.');
            }

            if ($user->wallet_balance < $swap->amount) {
                return redirect()->to(route('dashboard') . '#credit_swap')->with('error', 'Insufficient AVC balance to sell on this buy offer.');
            }

            $user->wallet_balance -= $swap->amount;
            $user->save();

            $swap->seller_id = $user->id;
            $swap->payment_details = $request->payment_details;
            $swap->status = 'pending_payment';
            $swap->save();

            $message = 'You responded as the seller on the buy offer. ' . format_avc($swap->amount) .
                ' are held in escrow. Once the buyer pays you, release the AVC.';
        } else {
            // Sell offer: responder is the buyer
            $swap->buyer_id = $user->id;
            $swap->status = 'pending_payment';
            $swap->save();

            $message = 'Purchase requested! Please send payment to the seller\'s account and wait for them to release the AVC.';
        }

        return redirect()->to(route('dashboard') . '#credit_swap')->with('success', $message);
    }

    public function releaseCreditSwap(Request $request, $id)
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user) {
            return redirect()->back()->with('error', 'User authentication required.');
        }

        $swap = CreditSwap::findOrFail($id);

        $isSeller = $swap->offer_type === 'buy'
            ? $swap->seller_id === $user->id
            : $swap->user_id === $user->id;

        if (!$isSeller && $user->role !== 'admin') {
            return redirect()->to(route('dashboard') . '#credit_swap')->with('error', 'Unauthorized to release this AVC Marketplace trade.');
        }

        if (!in_array($swap->status, ['active', 'in_deal', 'pending_payment'])) {
            return redirect()->to(route('dashboard') . '#credit_swap')->with('error', 'This AVC Marketplace trade cannot be released.');
        }

        $buyer = $swap->offer_type === 'buy' ? User::find($swap->user_id) : ($swap->buyer ?? User::find($request->buyer_id));

        if (!$buyer) {
            return redirect()->to(route('dashboard') . '#credit_swap')->with('error', 'Buyer account not found for AVC release.');
        }

        $sellerName = $swap->offer_type === 'buy'
            ? ($swap->responder->name ?? 'Investor')
            : ($swap->seller?->name ?? 'Investor');

        $escrowUserId = $swap->offer_type === 'buy' ? $swap->seller_id : $swap->user_id;

        $swap->buyer_id = $buyer->id;
        $swap->status = 'completed';
        $swap->save();

        // Release escrowed funds to Buyer's wallet balance
        $buyer->wallet_balance += $swap->amount;
        $buyer->save();

        // Record audit transactions for both parties
        Transaction::create([
            'user_id'     => $escrowUserId,
            'type'        => 'withdrawal',
            'amount'      => $swap->amount,
            'reference'   => $swap->reference,
            'description' => 'AVC Marketplace — released to ' . $buyer->name . ' (' . ($buyer->account_id ?? $buyer->email) . ')',
            'status'      => 'completed',
        ]);

        Transaction::create([
            'user_id'     => $buyer->id,
            'type'        => 'deposit',
            'amount'      => $swap->amount,
            'reference'   => $swap->reference,
            'description' => 'AVC Marketplace — received from ' . $sellerName . ' (' . ($swap->country ? $swap->country . ' · ' : '') . ')',
            'status'      => 'completed',
        ]);

        return redirect()->to(route('dashboard') . '#credit_swap')->with('success', 'AVC released successfully! ' . format_avc($swap->amount) . ' credited to ' . $buyer->name . '\'s AVC balance.');
    }

    public function cancelCreditSwap(Request $request, $id)
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user) {
            return redirect()->back()->with('error', 'User authentication required.');
        }

        $swap = CreditSwap::findOrFail($id);

        $isSeller = $swap->offer_type === 'buy'
            ? $swap->seller_id === $user->id
            : $swap->user_id === $user->id;

        $isBuyPoster = $swap->offer_type === 'buy' && $swap->user_id === $user->id && in_array($swap->status, ['pending', 'active']);

        if (!$isSeller && !$isBuyPoster && $user->role !== 'admin') {
            return redirect()->to(route('dashboard') . '#credit_swap')->with('error', 'Unauthorized to cancel this listing.');
        }

        if ($swap->status === 'completed') {
            return redirect()->to(route('dashboard') . '#credit_swap')->with('error', 'Completed AVC Marketplace trades cannot be cancelled.');
        }

        $swap->status = 'cancelled';
        $swap->save();

        // Return escrowed AVC back to the escrow holder's wallet balance
        $holder = $swap->offer_type === 'buy'
            ? ($swap->seller_id ? User::find($swap->seller_id) : null)
            : User::find($swap->user_id);

        if ($holder) {
            $holder->wallet_balance += $swap->amount;
            $holder->save();
        }

        return redirect()->to(route('dashboard') . '#credit_swap')->with('success', $holder
            ? 'Offer cancelled. ' . format_avc($swap->amount) . ' returned to the seller\'s balance.'
            : 'Offer cancelled.');
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
