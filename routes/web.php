<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\InvestController;
use App\Http\Controllers\MarketplaceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $ref = request()->query('ref');
    if ($ref) {
        return redirect()->route('register', ['ref' => $ref]);
    }
    $properties = \App\Models\Property::where('status', 'active')->orderBy('id', 'desc')->get();
    $projects = \App\Models\Project::where('status', 'active')->orderBy('id', 'desc')->get();
    return view('welcome', compact('properties', 'projects'));
});

// User Investment Dashboard
Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
Route::post('/deposit', [UserDashboardController::class, 'deposit'])->name('deposit.store')->middleware('throttle:forms');
Route::post('/deposit/evidence/{id}', [UserDashboardController::class, 'uploadEvidence'])->name('deposit.evidence')->middleware('throttle:forms');
Route::post('/withdraw', [UserDashboardController::class, 'withdraw'])->name('withdraw.store')->middleware('throttle:forms');
Route::post('/send-funds', [UserDashboardController::class, 'sendFunds'])->name('send-funds.store')->middleware('throttle:forms');
Route::post('/property/{property}/purchase', [UserDashboardController::class, 'purchaseProperty'])->name('property.purchase')->middleware('throttle:forms');
Route::post('/kyc/submit', [UserDashboardController::class, 'submitKyc'])->name('kyc.submit')->middleware('throttle:forms');
Route::post('/profile/update-info', [UserDashboardController::class, 'updateProfile'])->name('profile.update_info')->middleware('throttle:forms');
Route::post('/credit-swap/create', [UserDashboardController::class, 'createCreditSwap'])->name('credit-swap.create')->middleware('throttle:forms');
Route::post('/credit-swap/deal/{id}', [UserDashboardController::class, 'dealCreditSwap'])->name('credit-swap.deal')->middleware('throttle:forms');
Route::post('/credit-swap/update/{id}', [UserDashboardController::class, 'updateCreditSwap'])->name('credit-swap.update')->middleware('throttle:forms');
Route::post('/credit-swap/repost/{id}', [UserDashboardController::class, 'repostCreditSwap'])->name('credit-swap.repost')->middleware('throttle:forms');
Route::post('/credit-swap/{id}/buy', [UserDashboardController::class, 'buyCreditSwap'])->name('credit-swap.buy')->middleware('throttle:forms');
Route::post('/credit-swap/{id}/release', [UserDashboardController::class, 'releaseCreditSwap'])->name('credit-swap.release')->middleware('throttle:forms');
Route::post('/credit-swap/{id}/cancel', [UserDashboardController::class, 'cancelCreditSwap'])->name('credit-swap.cancel')->middleware('throttle:forms');
Route::post('/card/apply', [UserDashboardController::class, 'applyCard'])->name('card.apply')->middleware('throttle:forms');

// Admin Platform Management Dashboard
Route::prefix('admin')->name('admin.')->middleware(['admin', 'throttle:admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::post('/deposit/instructions/{id}', [AdminDashboardController::class, 'sendInstructions'])->name('deposit.instructions');
    Route::post('/deposit/approve/{id}', [AdminDashboardController::class, 'approveDeposit'])->name('deposit.approve');
    Route::post('/deposit/reject/{id}', [AdminDashboardController::class, 'rejectDeposit'])->name('deposit.reject');
    Route::post('/withdrawal/approve/{id}', [AdminDashboardController::class, 'approveWithdrawal'])->name('withdrawal.approve');
    Route::post('/withdrawal/reject/{id}', [AdminDashboardController::class, 'rejectWithdrawal'])->name('withdrawal.reject');
    Route::post('/property/store', [AdminDashboardController::class, 'storeProperty'])->name('property.store');
    Route::get('/property/{id}/edit', [AdminDashboardController::class, 'editProperty'])->name('property.edit');
    Route::post('/property/update/{id}', [AdminDashboardController::class, 'updateProperty'])->name('property.update');
    Route::post('/property/delete/{id}', [AdminDashboardController::class, 'deleteProperty'])->name('property.delete');
    Route::post('/project/store', [AdminDashboardController::class, 'storeProject'])->name('project.store');
    Route::get('/project/{id}/edit', [AdminDashboardController::class, 'editProject'])->name('project.edit');
    Route::post('/project/update/{id}', [AdminDashboardController::class, 'updateProject'])->name('project.update');
    Route::post('/project/delete/{id}', [AdminDashboardController::class, 'deleteProject'])->name('project.delete');
    Route::post('/referral-bonus', [AdminDashboardController::class, 'awardReferralBonus'])->name('referral-bonus');
    Route::post('/kyc/approve/{id}', [AdminDashboardController::class, 'approveKyc'])->name('kyc.approve');
    Route::post('/kyc/reject/{id}', [AdminDashboardController::class, 'rejectKyc'])->name('kyc.reject');
    Route::post('/users/{id}/impersonate', [AdminDashboardController::class, 'impersonate'])->name('users.impersonate');
    Route::post('/card/approve/{id}', [AdminDashboardController::class, 'approveCard'])->name('card.approve');
    Route::post('/card/reject/{id}', [AdminDashboardController::class, 'rejectCard'])->name('card.reject');
    Route::post('/credit-swap/approve/{id}', [AdminDashboardController::class, 'approveCreditSwap'])->name('credit-swap.approve');
    Route::post('/credit-swap/reject/{id}', [AdminDashboardController::class, 'rejectCreditSwap'])->name('credit-swap.reject');
    Route::post('/credit-swap/complete/{id}', [AdminDashboardController::class, 'completeCreditSwap'])->name('credit-swap.complete');
    Route::post('/credit-swap/pause/{id}', [AdminDashboardController::class, 'pauseCreditSwap'])->name('credit-swap.pause');
    Route::post('/credit-swap/cancel-deal/{id}', [AdminDashboardController::class, 'cancelCreditSwapDeal'])->name('credit-swap.cancel-deal');
    Route::post('/gallery-image/delete/{id}', [AdminDashboardController::class, 'deleteGalleryImage'])->name('gallery.delete');
    Route::post('/project/{id}/review', [AdminDashboardController::class, 'storeProjectReview'])->name('project-review.store');
    Route::post('/project-review/delete/{id}', [AdminDashboardController::class, 'deleteProjectReview'])->name('project-review.delete');
    Route::post('/settings/save', [AdminDashboardController::class, 'saveSettings'])->name('settings.save');
    Route::post('/settings/branding', [AdminDashboardController::class, 'saveBranding'])->name('settings.branding');
    Route::post('/settings/account', [AdminDashboardController::class, 'updateAdminAccount'])->name('settings.account');
});

// Stop impersonation - must be outside the admin middleware group so the impersonated user can leave
Route::post('/admin/impersonate/stop', [AdminDashboardController::class, 'stopImpersonation'])->name('admin.impersonate.stop')->middleware('throttle:forms');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update')->middleware('throttle:forms');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy')->middleware('throttle:forms');
});

Route::get('/properties', [PropertyController::class, 'index'])->name('properties.index');
Route::get('/property/{property}', [PropertyController::class, 'show'])->name('property.show');

// AVC CreditSwap Marketplace (admin-escrowed, Telegram-mediated)
Route::get('/marketplace', [MarketplaceController::class, 'index'])->name('marketplace');
Route::middleware('auth')->group(function () {
    Route::post('/property/{property}/save', [PropertyController::class, 'toggleSave'])->name('property.save')->middleware('throttle:forms');
});

// Project Marketplace & My Portfolio Routes
use App\Http\Controllers\ProjectMarketplaceController;
use App\Http\Controllers\SharePurchaseController;
use App\Http\Controllers\PortfolioController;

Route::get('/project-marketplace', [ProjectMarketplaceController::class, 'index'])->name('marketplace.index');
Route::get('/project-marketplace/{project}', [ProjectMarketplaceController::class, 'show'])->name('marketplace.show');
Route::get('/project-marketplace/{project}/document/{document}', [ProjectMarketplaceController::class, 'downloadDocument'])->name('marketplace.document.download');
Route::post('/project-marketplace/{project}/calculate', [SharePurchaseController::class, 'calculate'])->name('share.calculate');

Route::middleware('auth')->group(function () {
    Route::post('/project-marketplace/{project}/buy', [SharePurchaseController::class, 'store'])->name('share.buy')->middleware('throttle:forms');
    Route::post('/project-marketplace/{project}/save', [InvestController::class, 'toggleSave'])->name('project.save')->middleware('throttle:forms');
    Route::post('/project-marketplace/{project}/review', [InvestController::class, 'storeReview'])->name('project.review')->middleware('throttle:forms');
    Route::get('/my-portfolio', [PortfolioController::class, 'index'])->name('portfolio.index');
    Route::get('/my-portfolio/cycle/{cycle}/receipt', [PortfolioController::class, 'downloadReceipt'])->name('portfolio.receipt');
});

// Legacy redirection aliases
Route::get('/invest', function () {
    return redirect()->route('marketplace.index');
})->name('invest.index');
Route::get('/project/{project}', function ($project) {
    return redirect()->route('marketplace.show', $project);
})->name('project.show');

Route::get('/list-property', function () {
    return view('list-property');
});

Route::get('/team', function () {
    return view('team');
});

Route::get('/agent', function () {
    return view('agent');
});

Route::get('/affiliate', function () {
    return view('affiliate');
});

Route::get('/career', function () {
    return view('career');
});

Route::get('/resources', function () {
    return view('resources');
});

require __DIR__.'/auth.php';
