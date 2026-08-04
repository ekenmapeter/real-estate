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
use App\Models\CreditSwap;
use App\Models\ProjectImage;
use App\Models\ProjectReview;
use App\Models\PropertyImage;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
        $projects = Project::withCount(['investments', 'reviews'])->orderBy('created_at', 'desc')->get();
        $users = User::with(['investments.property', 'deposits', 'withdrawals', 'transactions', 'referrals'])
            ->where('role', 'user')
            ->orderBy('created_at', 'desc')
            ->get();
        $kycPendingUsers = User::where('kyc_status', 'pending')->whereNotNull('kyc_document_path')->orderBy('kyc_submitted_at', 'desc')->get();
        $referrers = User::whereHas('referrals')->with('referrals')->withCount('referrals')->orderBy('affiliate_earnings', 'desc')->get();
        $cards = Card::with('user')->orderBy('created_at', 'desc')->get();
        $creditSwaps = CreditSwap::with(['seller', 'buyer'])->orderBy('created_at', 'desc')->get();

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
            'creditSwaps',
        ))->with('settings', Setting::pluck('value', 'key')->all());
    }

    public function sendInstructions(Request $request, $id)
    {
        $deposit = Deposit::findOrFail($id);

        $request->validate([
            'beneficiary_method' => 'required|string',
            'beneficiary_account_number' => 'required|string',
            'beneficiary_account_name' => 'required|string',
        ]);

        $expirationMinutes = (int) ($request->expiration_minutes ?? Setting::get('default_expiration_minutes', 20));

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
            'gallery_urls' => 'nullable|string',
            'gallery' => 'nullable|array',
            'gallery.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
        ]);

        $property = Property::create([
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

        $this->syncGallery($property, 'properties', $request);

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
            'gallery_urls' => 'nullable|string',
            'gallery' => 'nullable|array',
            'gallery.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
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

        $this->syncGallery($property, 'properties', $request);

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

        foreach ($property->images as $image) {
            if (!Str::startsWith($image->image_path, ['http://', 'https://'])) {
                Storage::disk('public')->delete($image->image_path);
            }
        }

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
            'gallery_urls' => 'nullable|string',
            'gallery' => 'nullable|array',
            'gallery.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
        ]);

        $documentPath = null;
        if ($request->hasFile('document')) {
            $documentPath = $request->file('document')->store('project-documents', 'public');
        }

        $project = Project::create([
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

        $this->syncGallery($project, 'projects', $request);

        return redirect()->route('admin.dashboard', ['tab' => 'projects'])->with('success', 'New investment project created successfully!');
    }

    public function editProject($id)
    {
        $project = Project::with(['reviews.user'])->findOrFail($id);

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
            'gallery_urls' => 'nullable|string',
            'gallery' => 'nullable|array',
            'gallery.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
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

        $this->syncGallery($project, 'projects', $request);

        return redirect()->route('admin.dashboard', ['tab' => 'projects'])->with('success', 'Project "' . $project->title . '" updated successfully.');
    }

    public function deleteProject($id)
    {
        $project = Project::findOrFail($id);

        if ($project->document_path) {
            Storage::disk('public')->delete($project->document_path);
        }

        foreach ($project->images as $image) {
            if (!Str::startsWith($image->image_path, ['http://', 'https://'])) {
                Storage::disk('public')->delete($image->image_path);
            }
        }

        $project->delete();

        return redirect()->back()->with('success', 'Project "' . $project->title . '" deleted.');
    }

    public function storeProjectReview(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
            'reviewer_name' => 'required|string|max:255',
        ]);

        $project = Project::findOrFail($id);

        ProjectReview::create([
            'project_id' => $project->id,
            'user_id' => null,
            'rating' => $request->rating,
            'review' => $request->review,
            'reviewer_name' => $request->reviewer_name,
            'is_admin' => true,
        ]);

        // Recalculate average rating for project
        $newAvg = $project->reviews()->avg('rating');
        $project->update(['rating' => round($newAvg, 2)]);

        return redirect()->back()->with('success', 'Review added for "' . $project->title . '".');
    }

    public function deleteProjectReview($id)
    {
        $review = ProjectReview::findOrFail($id);
        $project = $review->project;
        $review->delete();

        // Recalculate rating for project
        $avg = $project->reviews()->avg('rating');
        $project->update(['rating' => $avg ? round($avg, 2) : 0.00]);

        return redirect()->back()->with('success', 'Review deleted.');
    }

    protected function syncGallery($owner, string $kind, Request $request): void
    {
        foreach ($owner->images as $image) {
            if (!Str::startsWith($image->image_path, ['http://', 'https://'])) {
                Storage::disk('public')->delete($image->image_path);
            }
        }

        $owner->images()->delete();

        $order = 0;

        $urls = collect(preg_split('/\r\n|\r|\n/', (string) $request->input('gallery_urls', '')))
            ->map(fn ($url) => trim($url))
            ->filter()
            ->values();

        foreach ($urls as $url) {
            $owner->images()->create(['image_path' => $url, 'sort_order' => $order++]);
        }

        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $path = $file->store('uploads/galleries/' . $kind, 'public');
                $owner->images()->create(['image_path' => $path, 'sort_order' => $order++]);
            }
        }
    }

    public function deleteGalleryImage($id)
    {
        $image = ProjectImage::where('id', $id)->first() ?? PropertyImage::where('id', $id)->first();

        if (!$image) {
            abort(404);
        }

        if (!Str::startsWith($image->image_path, ['http://', 'https://'])) {
            Storage::disk('public')->delete($image->image_path);
        }

        $image->delete();

        return redirect()->back()->with('success', 'Gallery image removed.');
    }

    public function saveSettings(Request $request)
    {
        $request->validate([
            'beneficiary_method' => 'required|string|max:50',
            'beneficiary_account_number' => 'required|string|max:100',
            'beneficiary_account_name' => 'required|string|max:100',
            'reference_prefix' => 'nullable|string|max:20',
            'default_expiration_minutes' => 'required|integer|min:5|max:1440',
            'min_deposit_amount' => 'required|numeric|min:1',
            'referral_bonus_amount' => 'required|numeric|min:0',
            'support_email' => 'required|email',
            'telegram_handle' => 'nullable|string|max:50',
        ]);

        Setting::set('beneficiary_method', $request->beneficiary_method);
        Setting::set('beneficiary_account_number', $request->beneficiary_account_number);
        Setting::set('beneficiary_account_name', $request->beneficiary_account_name);
        Setting::set('reference_prefix', $request->reference_prefix ?: 'RDR');
        Setting::set('default_expiration_minutes', $request->default_expiration_minutes);
        Setting::set('min_deposit_amount', $request->min_deposit_amount);
        Setting::set('referral_bonus_amount', $request->referral_bonus_amount);
        Setting::set('support_email', $request->support_email);
        Setting::set('telegram_handle', ltrim(trim($request->telegram_handle ?? ''), '@'));

        return redirect()->route('admin.dashboard', ['tab' => 'settings'])->with('success', 'Platform settings saved successfully!');
    }

    public function saveBranding(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:60',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
        ]);

        Setting::set('site_name', trim($request->site_name));

        if ($request->hasFile('logo')) {
            $old = Setting::get('logo_path', '');
            if ($old) {
                Storage::disk('public')->delete($old);
            }

            $path = $request->file('logo')->storeAs('branding', 'logo.' . $request->file('logo')->extension(), 'public');
            Setting::set('logo_path', $path);
        }

        return redirect()->route('admin.dashboard', ['tab' => 'settings'])->with('success', 'Site branding updated successfully!');
    }

    public function updateAdminAccount(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
            'current_password' => 'required|current_password',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $admin = Auth::user();
        $admin->name = $request->name;
        $admin->email = $request->email;

        if ($request->filled('password')) {
            $admin->password = Hash::make($request->password);
        }

        $admin->save();

        return redirect()->route('admin.dashboard', ['tab' => 'settings'])->with('success', 'Admin account updated successfully!');
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
        $card->card_brand = $card->card_brand ?: ((rand(0, 1) === 0) ? 'Visa' : 'Mastercard');
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

    public function approveCreditSwap($id)
    {
        $swap = CreditSwap::findOrFail($id);

        if ($swap->status !== 'pending') {
            return redirect()->back()->with('error', 'This marketplace offer has already been reviewed.');
        }

        if (empty($swap->listing_number)) {
            $max = (int) CreditSwap::whereNotNull('listing_number')->max('listing_number');
            $swap->listing_number = str_pad((string) ($max + 1), 4, '0', STR_PAD_LEFT);
        }

        $swap->status = 'active';
        $swap->appendLog('Listing approved and published as #' . $swap->listing_number, Auth::user()?->name ?? 'Admin');
        $swap->save();

        return redirect()->back()->with('success', 'Marketplace offer ' . $swap->listingLabel() . ' approved and is now live for ' . ucfirst($swap->offer_type) . ' ' . format_avc($swap->amount) . '.');
    }

    public function rejectCreditSwap(Request $request, $id)
    {
        $request->validate(['admin_note' => 'nullable|string|max:1000']);

        $swap = CreditSwap::findOrFail($id);

        if ($swap->status !== 'pending') {
            return redirect()->back()->with('error', 'This marketplace offer has already been reviewed.');
        }

        $swap->status = 'rejected';
        $swap->admin_note = $request->admin_note ?: 'Offer did not meet marketplace requirements.';
        $swap->appendLog('Listing rejected. Reason: ' . $swap->admin_note, Auth::user()?->name ?? 'Admin');
        $swap->save();

        // Refund escrowed AVC to the seller (sell offers hold AVC in escrow on posting)
        if ($swap->offer_type === 'sell') {
            $poster = User::find($swap->user_id);
            if ($poster) {
                $poster->wallet_balance += $swap->amount;
                $poster->save();
            }
        }

        return redirect()->back()->with('success', 'Marketplace offer ' . $swap->listingLabel() . ' rejected. Escrowed AVC refunded and the user has been notified.');
    }

    public function pauseCreditSwap($id)
    {
        $swap = CreditSwap::findOrFail($id);

        if ($swap->status === 'paused') {
            $swap->status = 'active';
            $swap->appendLog('Listing resumed by admin.', Auth::user()?->name ?? 'Admin');
            $swap->save();

            return redirect()->back()->with('success', 'Marketplace offer ' . $swap->listingLabel() . ' resumed. It is visible again.');
        }

        if (!in_array($swap->status, ['active', 'in_deal'])) {
            return redirect()->back()->with('error', 'Only active or in-progress listings can be paused.');
        }

        $swap->status = 'paused';
        $swap->appendLog('Listing paused by admin.', Auth::user()?->name ?? 'Admin');
        $swap->save();

        return redirect()->back()->with('success', 'Marketplace offer ' . $swap->listingLabel() . ' paused. It is hidden until resumed.');
    }

    public function completeCreditSwap($id)
    {
        $swap = CreditSwap::with(['seller', 'buyer', 'responder'])->findOrFail($id);

        if (!in_array($swap->status, ['in_deal', 'pending_payment', 'active', 'paused'])) {
            return redirect()->back()->with('error', 'This deal can no longer be completed.');
        }

        $escrowHolder = $swap->escrowHolder();
        $creditBuyer = $swap->creditBuyer();

        if (!$escrowHolder) {
            return redirect()->back()->with('error', 'No escrow holder found for this deal. Credits cannot be released.');
        }

        if (!$creditBuyer) {
            return redirect()->back()->with('error', 'No buyer matched for this deal yet. A buyer must start the deal first.');
        }

        if ($escrowHolder->wallet_balance < $swap->amount) {
            return redirect()->back()->with('error', 'Escrow balance is insufficient for ' . $swap->listingLabel() . ' — deal cannot be completed.');
        }

        $escrowHolder->wallet_balance -= $swap->amount;
        $escrowHolder->save();

        $creditBuyer->wallet_balance += $swap->amount;
        $creditBuyer->save();

        $swap->status = 'completed';
        $swap->appendLog('Deal completed by admin. ' . format_avc($swap->amount) . ' released from escrow to ' . ($creditBuyer->name ?? 'buyer') . '.', Auth::user()?->name ?? 'Admin');
        $swap->save();

        Transaction::create([
            'user_id' => $escrowHolder->id,
            'type' => 'withdrawal',
            'amount' => $swap->amount,
            'reference' => $swap->reference,
            'description' => 'AVC Marketplace #' . $swap->listingLabel() . ' — escrow released to ' . ($creditBuyer->name ?? 'buyer'),
            'status' => 'completed',
        ]);

        Transaction::create([
            'user_id' => $creditBuyer->id,
            'type' => 'deposit',
            'amount' => $swap->amount,
            'reference' => $swap->reference,
            'description' => 'AVC Marketplace #' . $swap->listingLabel() . ' — credits received via admin escrow',
            'status' => 'completed',
        ]);

        return redirect()->back()->with('success', 'Deal #' . $swap->listingLabel() . ' completed! ' . format_avc($swap->amount) . ' released to the buyer. The listing has been removed from the marketplace.');
    }

    public function cancelCreditSwapDeal($id)
    {
        $swap = CreditSwap::findOrFail($id);

        if (!in_array($swap->status, ['in_deal', 'pending_payment', 'paused'])) {
            return redirect()->back()->with('error', 'This deal cannot be cancelled.');
        }

        $holder = $swap->escrowHolder();
        if ($holder) {
            $holder->wallet_balance += $swap->amount;
            $holder->save();
        }

        $swap->status = 'active';
        $swap->buyer_id = null;
        $swap->seller_id = $swap->offer_type === 'buy' ? null : $swap->seller_id;
        $swap->payment_details = $swap->offer_type === 'sell' ? $swap->payment_details : null;
        $swap->appendLog('Deal cancelled by admin. Escrow refunded to ' . ($holder->name ?? 'holder') . '.', Auth::user()?->name ?? 'Admin');
        $swap->save();

        return redirect()->back()->with('success', 'Deal on ' . $swap->listingLabel() . ' cancelled and escrow refunded. The listing is active again.');
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
