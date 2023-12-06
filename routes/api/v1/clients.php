<?php

use App\Http\Controllers\V1\ClientController;
use Illuminate\Support\Facades\Route;

Route::prefix('clients')->group(function () {
    Route::post('/', [ClientController::class, 'post']);
    Route::put('/{uuid}', [ClientController::class, 'put']);
    Route::get('/{uuid}', [ClientController::class, 'find']);
    Route::get('/', [ClientController::class, 'get']);
    Route::delete('/{uuid}', [ClientController::class, 'delete']);
});
