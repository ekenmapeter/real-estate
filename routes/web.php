<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\AdminDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// User Investment Dashboard
Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
Route::post('/deposit', [UserDashboardController::class, 'deposit'])->name('deposit.store');
Route::post('/withdraw', [UserDashboardController::class, 'withdraw'])->name('withdraw.store');
Route::post('/send-funds', [UserDashboardController::class, 'sendFunds'])->name('send-funds.store');
Route::post('/buy-shares', [UserDashboardController::class, 'buyShares'])->name('buy-shares.store');

// Admin Platform Management Dashboard
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::post('/deposit/approve/{id}', [AdminDashboardController::class, 'approveDeposit'])->name('deposit.approve');
    Route::post('/deposit/reject/{id}', [AdminDashboardController::class, 'rejectDeposit'])->name('deposit.reject');
    Route::post('/withdrawal/approve/{id}', [AdminDashboardController::class, 'approveWithdrawal'])->name('withdrawal.approve');
    Route::post('/withdrawal/reject/{id}', [AdminDashboardController::class, 'rejectWithdrawal'])->name('withdrawal.reject');
    Route::post('/property/store', [AdminDashboardController::class, 'storeProperty'])->name('property.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/properties', function () {
    return view('properties');
});

Route::get('/list-property', function () {
    return view('list-property');
});

Route::get('/project-marketplace', function () {
    return view('project-marketplace');
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
