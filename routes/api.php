<?php

use App\Http\Controllers\Admin\SmsGatewayController;
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

Route::prefix('v1')->group(function () {
    Route::get('/gateways/balances', [SmsGatewayController::class, 'checkAllBalances']);
    Route::get('/gateways/{id}/balance', [SmsGatewayController::class, 'checkBalance']);
    Route::match(['get', 'post'], '/send-sms', [\App\Http\Controllers\User\AcnooSmsApiController::class, 'index'])->name('api.v1.send-sms');
    Route::match(['get', 'post'], '/check-balance', [\App\Http\Controllers\User\BalanceCheckController::class, 'index'])->name('api.v1.check-balance');
});
