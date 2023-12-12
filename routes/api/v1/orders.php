<?php

use App\Http\Controllers\V1\OrderController;
use Illuminate\Support\Facades\Route;

Route::prefix('orders')->group(function () {
    Route::post('/', [OrderController::class, 'post']);
    Route::put('/{uuid}', [OrderController::class, 'put']);
    Route::get('/{uuid}', [OrderController::class, 'find']);
    Route::get('/', [OrderController::class, 'get']);
    Route::delete('/{uuid}', [OrderController::class, 'delete']);
});
