<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/health-check', function () {
    return response()->json(['message' => 'OK'], 200);
});

Route::namespace('V1')->prefix('v1')->group(
    function () {
        /*
         * Call route files based on context
         * Example: require base_path('routes/api/v1/clients.php');
         */
        require base_path('routes/api/v1/clients.php');
    }
);
