<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\StreamerApiController;
use App\Http\Controllers\Api\DonasiApiController;
use App\Http\Controllers\Api\WithdrawApiController;

Route::post('/register',
[
    AuthApiController::class,
    'register'
]);

Route::post('/login',
[
    AuthApiController::class,
    'login'
]);

Route::get('/streamers',
[
    StreamerApiController::class,
    'index'
]);

Route::get('/streamers/{id}',
[
    StreamerApiController::class,
    'show'
]);

Route::middleware('auth:sanctum')
->group(function(){

    Route::get('/dashboard-summary',
    [
        AuthApiController::class,
        'dashboardSummary'
    ]);

    Route::get('/profile',
    [
        AuthApiController::class,
        'profile'
    ]);

    Route::post('/logout',
    [
        AuthApiController::class,
        'logout'
    ]);

    Route::post('/follow/{id}',
    [
        StreamerApiController::class,
        'follow'
    ]);

    Route::post('/unfollow/{id}',
    [
        StreamerApiController::class,
        'unfollow'
    ]);

    Route::post('/donate',
    [
        DonasiApiController::class,
        'store'
    ]);

    Route::get('/donation-history',
    [
        DonasiApiController::class,
        'history'
    ]);

    Route::post('/withdraw',
    [
        WithdrawApiController::class,
        'store'
    ]);

    Route::get('/withdraw-history',
    [
        WithdrawApiController::class,
        'history'
    ]);

});