<?php

use App\Http\Controllers\V1\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('products')->group(function () {
    Route::post('/', [ProductController::class, 'post']);
    Route::put('/{uuid}', [ProductController::class, 'put']);
    Route::get('/{uuid}', [ProductController::class, 'find']);
    Route::get('/', [ProductController::class, 'get']);
    Route::delete('/{uuid}', [ProductController::class, 'delete']);
});
