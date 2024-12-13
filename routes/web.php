<?php

use App\Http\Controllers\Auth\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;

Route::get('/', [TransactionController::class, 'list'])->name('transactions.list');

Route::middleware('guest')->group(function() {
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::middleware('auth')->group(function() {
    Route::get('/wallets', [WalletController::class, 'index'])->name('wallets.index');
    Route::get('/wallet/create', [WalletController::class, 'create'])->name('wallets.create');
    Route::post('/wallet/store', [WalletController::class, 'store'])->name('wallets.store');
    Route::delete('/wallet/{id}', [WalletController::class, 'destroy'])->name('wallets.destroy');
    Route::get('/transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
    Route::post('/transactions/store', [TransactionController::class, 'store'])->name('transactions.store');
    Route::delete('/transactions/{id}', [TransactionController::class, 'destroy'])->name('transactions.destroy');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});