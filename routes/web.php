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
    $properties = \App\Models\Property::where('status', 'published')->orderBy('id', 'desc')->get();
    $projects = \App\Models\Project::where('status', 'active')->orderBy('id', 'desc')->get();
    return view('welcome', compact('properties', 'projects'));
});

use App\Http\Controllers\DepositController;
use App\Http\Controllers\WithdrawalController;
use App\Http\Controllers\SavedWithdrawalMethodController;
use App\Http\Controllers\AdminDepositManagementController;
use App\Http\Controllers\AdminWithdrawalManagementController;
use App\Http\Controllers\AdminPaymentChannelController;

// User Investment Dashboard & AVC Deposit / Withdrawal Routes
Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
Route::middleware('auth')->group(function () {
    // Deposit Routes
    Route::get('/deposit', [DepositController::class, 'index'])->name('deposit.index');
    Route::get('/deposit/buy-avc', [DepositController::class, 'index']);
    Route::get('/deposit/channel/{method}', [DepositController::class, 'create'])->name('deposit.channel');
    Route::post('/deposit/create', [DepositController::class, 'store'])->name('deposit.create.store')->middleware('throttle:forms');
    Route::get('/deposit/{deposit}', [DepositController::class, 'show'])->name('deposit.show');
    Route::post('/deposit/{deposit}/proof', [DepositController::class, 'submitPaymentProof'])->name('deposit.proof.store')->middleware('throttle:forms');
    Route::post('/deposit/{deposit}/cancel', [DepositController::class, 'cancel'])->name('deposit.cancel')->middleware('throttle:forms');

    // Withdrawal Routes
    Route::get('/withdraw', [WithdrawalController::class, 'index'])->name('withdraw.index');
    Route::get('/withdraw/sell-avc', [WithdrawalController::class, 'index']);
    Route::post('/withdraw/create', [WithdrawalController::class, 'store'])->name('withdraw.create.store')->middleware('throttle:forms');
    Route::get('/withdraw/{withdrawal}', [WithdrawalController::class, 'show'])->name('withdraw.show');
    Route::post('/withdraw/{withdrawal}/cancel', [WithdrawalController::class, 'cancel'])->name('withdraw.cancel')->middleware('throttle:forms');

    // Finance Center & Transaction History Routes
    Route::get('/finance', [\App\Http\Controllers\FinanceController::class, 'overview'])->name('finance.overview');
    Route::get('/finance/transactions', [\App\Http\Controllers\FinanceController::class, 'transactions'])->name('finance.transactions');
    Route::get('/finance/transactions/export/csv', [\App\Http\Controllers\FinanceController::class, 'exportCsv'])->name('finance.transactions.export.csv');
    Route::get('/finance/transactions/{transaction}', [\App\Http\Controllers\FinanceController::class, 'transactionShow'])->name('finance.transactions.show');

    // Dedicated Finance Team Routes
    Route::get('/finance/team', [\App\Http\Controllers\FinanceTeamController::class, 'index'])->name('finance.team.index');
    Route::get('/finance/team/create', [\App\Http\Controllers\FinanceTeamController::class, 'create'])->name('finance.team.create');
    Route::post('/finance/team/store', [\App\Http\Controllers\FinanceTeamController::class, 'store'])->name('finance.team.store')->middleware('throttle:forms');
    Route::get('/finance/team/request/{request_id}', [\App\Http\Controllers\FinanceTeamController::class, 'show'])->name('finance.team.show');
    Route::post('/finance/team/request/{request_id}/evidence', [\App\Http\Controllers\FinanceTeamController::class, 'uploadEvidence'])->name('finance.team.evidence.store')->middleware('throttle:forms');
    Route::post('/finance/team/request/{request_id}/cancel', [\App\Http\Controllers\FinanceTeamController::class, 'cancel'])->name('finance.team.cancel')->middleware('throttle:forms');

    // Saved Withdrawal Methods Routes
    Route::post('/saved-withdrawal-methods', [SavedWithdrawalMethodController::class, 'store'])->name('saved-withdrawal-methods.store');
    Route::delete('/saved-withdrawal-methods/{savedMethod}', [SavedWithdrawalMethodController::class, 'destroy'])->name('saved-withdrawal-methods.destroy');
    Route::post('/saved-withdrawal-methods/{savedMethod}/default', [SavedWithdrawalMethodController::class, 'setDefault'])->name('saved-withdrawal-methods.default');
});

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
    
    // Deposit Management Routes
    Route::get('/deposits', [AdminDepositManagementController::class, 'index'])->name('deposits.index');
    Route::post('/deposits/{deposit}/assign-instructions', [AdminDepositManagementController::class, 'assignInstructions'])->name('deposits.assign-instructions');
    Route::post('/deposits/{deposit}/credit-avc', [AdminDepositManagementController::class, 'creditAvc'])->name('deposits.credit-avc');
    Route::post('/deposits/{deposit}/request-info', [AdminDepositManagementController::class, 'requestInfo'])->name('deposits.request-info');
    Route::post('/deposits/{deposit}/reject', [AdminDepositManagementController::class, 'reject'])->name('deposits.reject');
    Route::post('/deposits/{deposit}/extend-timer', [AdminDepositManagementController::class, 'extendTimer'])->name('deposits.extend-timer');

    // Withdrawal Management Routes
    Route::get('/withdrawals', [AdminWithdrawalManagementController::class, 'index'])->name('withdrawals.index');
    Route::post('/withdrawals/{withdrawal}/approve', [AdminWithdrawalManagementController::class, 'approve'])->name('withdrawals.approve');
    Route::post('/withdrawals/{withdrawal}/complete', [AdminWithdrawalManagementController::class, 'complete'])->name('withdrawals.complete');
    Route::post('/withdrawals/{withdrawal}/reject', [AdminWithdrawalManagementController::class, 'reject'])->name('withdrawals.reject');

    // Payment Channel Management Routes
    Route::resource('payment-channels', AdminPaymentChannelController::class)->except(['create', 'show', 'edit']);

    // Admin Finance Requests Management Routes
    Route::get('/finance-requests', [\App\Http\Controllers\AdminFinanceRequestController::class, 'index'])->name('finance-requests.index');
    Route::get('/finance-requests/{id}', [\App\Http\Controllers\AdminFinanceRequestController::class, 'show'])->name('finance-requests.show');
    Route::post('/finance-requests/{id}/assign-instructions', [\App\Http\Controllers\AdminFinanceRequestController::class, 'assignInstructions'])->name('finance-requests.assign-instructions');
    Route::post('/finance-requests/{id}/approve', [\App\Http\Controllers\AdminFinanceRequestController::class, 'approve'])->name('finance-requests.approve');
    Route::post('/finance-requests/{id}/reject', [\App\Http\Controllers\AdminFinanceRequestController::class, 'reject'])->name('finance-requests.reject');

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

// Properties Marketplace — user listing & inquiry flows
Route::middleware('auth')->group(function () {
    Route::get('/list-property', [PropertyController::class, 'create'])->name('properties.create');
    Route::post('/list-property', [PropertyController::class, 'store'])->name('properties.store')->middleware('throttle:forms');
    Route::get('/property/{property}/edit', [PropertyController::class, 'edit'])->name('properties.edit');
    Route::post('/property/{property}/update', [PropertyController::class, 'update'])->name('properties.update')->middleware('throttle:forms');
    Route::post('/property/{property}/delete', [PropertyController::class, 'destroy'])->name('properties.destroy')->middleware('throttle:forms');
    Route::post('/property/{property}/pause', [PropertyController::class, 'togglePause'])->name('properties.pause')->middleware('throttle:forms');
    Route::post('/property/{property}/mark-sold', [PropertyController::class, 'markSold'])->name('properties.sold')->middleware('throttle:forms');
    Route::post('/property/{property}/mark-rented', [PropertyController::class, 'markRented'])->name('properties.rented')->middleware('throttle:forms');
    Route::get('/my-properties', [PropertyController::class, 'myListings'])->name('properties.mine');
    Route::get('/my-properties/inquiries', [PropertyController::class, 'myInquiries'])->name('properties.inquiries');
    Route::get('/my-properties/viewing-requests', [PropertyController::class, 'viewingRequests'])->name('properties.viewing-requests');
    Route::get('/my-properties/saved', [PropertyController::class, 'savedProperties'])->name('properties.saved');
});

Route::post('/property/{property}/inquiry/{type}', [PropertyController::class, 'storeInquiry'])->name('properties.inquiry')->middleware('throttle:forms');
Route::get('/property-inquiry/{inquiry}/confirmation', [PropertyController::class, 'viewingConfirmation'])->name('properties.viewing.confirmation');
Route::post('/property/{property}/report', [PropertyController::class, 'report'])->name('properties.report')->middleware('throttle:forms');

// Documents module
use App\Http\Controllers\DocumentController;
Route::middleware('auth')->group(function () {
    Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
    Route::get('/documents/zip', [DocumentController::class, 'zip'])->name('documents.zip');
    Route::post('/documents/statement', [DocumentController::class, 'statement'])->name('documents.statement')->middleware('throttle:forms');
    Route::get('/documents/{document}', [DocumentController::class, 'view'])->name('documents.view');
    Route::get('/documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
    Route::get('/documents/{document}/print', [DocumentController::class, 'print'])->name('documents.print');
    Route::post('/documents/{document}/share', [DocumentController::class, 'share'])->name('documents.share')->middleware('throttle:forms');
});
Route::get('/documents/share/{token}', [DocumentController::class, 'shared'])->name('documents.shared');

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

// Admin Properties Marketplace module
use App\Http\Controllers\AdminPropertyController;
Route::prefix('admin')->name('admin.')->middleware(['admin', 'throttle:admin'])->group(function () {
    Route::get('/properties', [AdminPropertyController::class, 'index'])->name('properties.index');
    Route::get('/properties/{property}', [AdminPropertyController::class, 'review'])->name('properties.review');
    Route::post('/properties/{property}/approve', [AdminPropertyController::class, 'approve'])->name('properties.approve');
    Route::post('/properties/{property}/reject', [AdminPropertyController::class, 'reject'])->name('properties.reject');
    Route::post('/properties/{property}/request-info', [AdminPropertyController::class, 'requestInfo'])->name('properties.request-info');
    Route::post('/properties/{property}/suspend', [AdminPropertyController::class, 'suspend'])->name('properties.suspend');
    Route::post('/properties/{property}/restore', [AdminPropertyController::class, 'restore'])->name('properties.restore');
    Route::post('/properties/{property}/feature', [AdminPropertyController::class, 'toggleFeatured'])->name('properties.feature');
    Route::post('/properties/{property}/delete', [AdminPropertyController::class, 'destroy'])->name('properties.destroy');
    Route::post('/properties/{property}/update', [AdminPropertyController::class, 'update'])->name('properties.update');
    Route::get('/properties/{property}/document/{document}', [AdminPropertyController::class, 'downloadDocument'])->name('properties.document.download');

    Route::get('/property-categories', [AdminPropertyController::class, 'categories'])->name('properties.categories');
    Route::post('/property-categories/store', [AdminPropertyController::class, 'storeCategory'])->name('properties.categories.store');
    Route::post('/property-categories/{category}/update', [AdminPropertyController::class, 'updateCategory'])->name('properties.categories.update');
    Route::post('/property-categories/{category}/delete', [AdminPropertyController::class, 'destroyCategory'])->name('properties.categories.delete');

    Route::get('/inquiries', [AdminPropertyController::class, 'inquiries'])->name('inquiries.index');
    Route::get('/inquiries/{inquiry}', [AdminPropertyController::class, 'inquiryShow'])->name('inquiries.show');
    Route::post('/inquiries/{inquiry}/status', [AdminPropertyController::class, 'inquiryUpdate'])->name('inquiries.status');
    Route::post('/inquiries/{inquiry}/connect', [AdminPropertyController::class, 'inquiryConnect'])->name('inquiries.connect');

    Route::get('/conversations', [AdminPropertyController::class, 'conversations'])->name('conversations.index');
    Route::post('/conversations/{conversation}/close', [AdminPropertyController::class, 'conversationClose'])->name('conversations.close');

    Route::get('/representatives', [AdminPropertyController::class, 'representatives'])->name('representatives.index');
    Route::post('/representatives/{user}/verify', [AdminPropertyController::class, 'verifyRepresentative'])->name('representatives.verify');
    Route::post('/representatives/{user}/reject', [AdminPropertyController::class, 'rejectRepresentative'])->name('representatives.reject');

    Route::get('/reports', [AdminPropertyController::class, 'reports'])->name('reports.index');
    Route::post('/reports/{report}/resolve', [AdminPropertyController::class, 'reportResolve'])->name('reports.resolve');
    Route::post('/reports/{report}/dismiss', [AdminPropertyController::class, 'reportDismiss'])->name('reports.dismiss');
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
