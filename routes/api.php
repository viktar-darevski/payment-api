<?php

use App\Http\Controllers\Api\V1\PaymentController;
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




Route::prefix("v1")->group(function () {
    Route::prefix('/payments')->group(function () {
        Route::post('/transaction', [PaymentController::class, 'create']);
    })->middleware('auth:api');
});
