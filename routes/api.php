<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\StreamerApiController;
use App\Http\Controllers\Api\DonasiApiController;
use App\Http\Controllers\Api\WithdrawApiController;

// ── GUEST ROUTES (Tanpa Auth) ──
Route::post('/register', [
    AuthApiController::class,
    'register'
]);

Route::post('/login', [
    AuthApiController::class,
    'login'
]);

Route::get('/streamers', [
    StreamerApiController::class,
    'index'
]);

Route::get('/streamers/{id}', [
    StreamerApiController::class,
    'show'
]);

// ── GUEST QRIS ROUTES ──
Route::post('/guest/donate-qris', [
    DonasiApiController::class,
    'guestDonateQris'
]);

Route::post('/guest/pay-onopay/{id}', [
    DonasiApiController::class,
    'guestPayOnopay'
]);

// ── AUTHENTICATED ROUTES ──
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/dashboard-summary', [
        AuthApiController::class,
        'dashboardSummary'
    ]);

    Route::get('/profile', [
        AuthApiController::class,
        'profile'
    ]);

    Route::post('/logout', [
        AuthApiController::class,
        'logout'
    ]);

    Route::post('/follow/{id}', [
        StreamerApiController::class,
        'follow'
    ]);

    Route::post('/unfollow/{id}', [
        StreamerApiController::class,
        'unfollow'
    ]);

    Route::post('/donate', [
        DonasiApiController::class,
        'store'
    ]);

    Route::get('/donation-history', [
        DonasiApiController::class,
        'history'
    ]);

    Route::get('/wallet-summary', [
        WithdrawApiController::class,
        'summary'
    ]);

    Route::post('/withdraw', [
        WithdrawApiController::class,
        'store'
    ]);

    Route::get('/withdraw-history', [
        WithdrawApiController::class,
        'history'
    ]);

    // ── QRIS DONATION (User Login) ──
    Route::post('/donate-qris', [
        DonasiApiController::class,
        'donateQris'
    ]);

    // ── PAYMENT DETAIL ──
    Route::get('/payment-detail/{id}', [
        DonasiApiController::class,
        'paymentDetail'
    ]);

    // ── CHECK PAYMENT ──
    Route::get('/check-payment/{id}', [
        DonasiApiController::class,
        'checkPayment'
    ]);

    // ── PAY ONOPAY (User Login) ──
    Route::post('/pay-onopay/{id}', [
        DonasiApiController::class,
        'payOnopay'
    ]);
});