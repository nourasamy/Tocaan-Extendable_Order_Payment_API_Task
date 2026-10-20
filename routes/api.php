<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\ConfigController;
use App\Http\Controllers\Api\GatewayController;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PaymentController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth'], function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('jwt.custom');
});

Route::middleware(['jwt.custom'])->group(function () {
    Route::apiResource('clients', ClientController::class);
    Route::apiResource('items', ItemController::class);
    Route::apiResource('gateways', GatewayController::class);

    // payments
    Route::apiResource('payments', PaymentController::class)->only(['index', 'show']);
    Route::get('payments/order/{order_id}', [PaymentController::class, 'getPaymentsByOrder']);

    // orders
    Route::apiResource('orders', OrderController::class);
    Route::group(['prefix' => 'orders'], function () {
        Route::post('{order}/change-status', [OrderController::class, 'changeStatus']);
        Route::post('{order}/pay', [OrderController::class, 'payOrder']);
    });

    // configuration
    Route::group(['prefix' => 'configuration'], function () {
        Route::get('tax-types', [ConfigController::class, 'getTaxTypes']);
        Route::get('discount-types', [ConfigController::class, 'getDiscountTypes']);
        Route::get('order-status', [ConfigController::class, 'getOrderStatuses']);

    });
});
