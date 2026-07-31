<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\PropertyController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $ref = request()->query('ref');
    if ($ref) {
        return redirect()->route('register', ['ref' => $ref]);
    }
    $properties = \App\Models\Property::where('status', 'active')->orderBy('id', 'desc')->get();
    return view('welcome', compact('properties'));
});

// User Investment Dashboard
Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
Route::post('/deposit', [UserDashboardController::class, 'deposit'])->name('deposit.store');
Route::post('/deposit/evidence/{id}', [UserDashboardController::class, 'uploadEvidence'])->name('deposit.evidence');
Route::post('/withdraw', [UserDashboardController::class, 'withdraw'])->name('withdraw.store');
Route::post('/send-funds', [UserDashboardController::class, 'sendFunds'])->name('send-funds.store');
Route::post('/buy-shares', [UserDashboardController::class, 'buyShares'])->name('buy-shares.store');
Route::post('/kyc/submit', [UserDashboardController::class, 'submitKyc'])->name('kyc.submit');

// Admin Platform Management Dashboard
Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
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
    Route::post('/referral-bonus', [AdminDashboardController::class, 'awardReferralBonus'])->name('referral-bonus');
    Route::post('/kyc/approve/{id}', [AdminDashboardController::class, 'approveKyc'])->name('kyc.approve');
    Route::post('/kyc/reject/{id}', [AdminDashboardController::class, 'rejectKyc'])->name('kyc.reject');
    Route::post('/users/{id}/impersonate', [AdminDashboardController::class, 'impersonate'])->name('users.impersonate');
});

// Stop impersonation - must be outside the admin middleware group so the impersonated user can leave
Route::post('/admin/impersonate/stop', [AdminDashboardController::class, 'stopImpersonation'])->name('admin.impersonate.stop');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/properties', [PropertyController::class, 'index']);
Route::get('/property/{property}', [PropertyController::class, 'show'])->name('property.show');

Route::get('/list-property', function () {
    return view('list-property');
});

Route::get('/project-marketplace', function () {
    $properties = \App\Models\Property::where('status', 'active')->orderBy('id', 'desc')->get();
    return view('project-marketplace', compact('properties'));
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
