<?php

use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', [UserController::class, 'fetch']);
    Route::put('user', [UserController::class, 'update']);
    Route::post('logout', [UserController::class, 'logout']);
    
    Route::apiResource('cart', CartController::class);
    Route::apiResource('address', AddressController::class);
    Route::apiResource('transaction', TransactionController::class);
});

Route::apiResource('product', ProductController::class);
Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);
