<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\TransactionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| All routes are JWT-protected except register/login.
|
*/

// Auth routes
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
    Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api');
    Route::get('/me', [AuthController::class, 'me'])->middleware('auth:api');
});

// User routes (admin can list/delete users)
Route::middleware('auth:api')->prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index']); // admin only
    Route::put('/update', [UserController::class, 'update']); // self-update
    Route::delete('/delete/{id?}', [UserController::class, 'destroy']); // self or admin delete
    Route::post('/{id}/mute', [UserController::class, 'mute']); // admin only
    Route::post('/{id}/ban', [UserController::class, 'ban']); // admin only
    Route::post('/{id}/activate', [UserController::class, 'activate']); // admin only
});

// Category CRUD
Route::middleware('auth:api')->apiResource('categories', CategoryController::class);

// Transaction CRUD
Route::middleware('auth:api')->apiResource('transactions', TransactionController::class);

// Transaction reports & charts
Route::middleware('auth:api')->prefix('reports')->group(function () {
    Route::get('monthly-summary/{month?}/{year?}', [TransactionController::class, 'monthlySummary']);
    Route::get('category-breakdown/{month?}/{year?}', [TransactionController::class, 'categoryBreakdown']);
    Route::get('recent/{limit?}', [TransactionController::class, 'recent']);
});

// Currency conversion
Route::middleware('auth:api')->prefix('currency')->group(function () {
    Route::post('convert', [TransactionController::class, 'convertCurrency']);
    Route::get('list', [TransactionController::class, 'currencies']);
});
