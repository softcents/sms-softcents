<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SSP as SSP;

Route::group(['as' => 'ssp.', 'prefix' => 'ssp', 'middleware' => ['auth', 'ssp']], function () {
    Route::get('/', [SSP\DashboardController::class, 'index'])->name('dashboard.root');
    Route::get('/dashboard', [SSP\DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/dashboard/data', [SSP\DashboardController::class, 'getDashboardData'])->name('dashboard.data');
    Route::get('/dashboard/sms-overview', [SSP\DashboardController::class, 'smsOverview'])->name('dashboard.sms-overview');
    Route::get('/dashboard/statistics', [SSP\DashboardController::class, 'yearlyStatistics'])->name('dashboard.statistics');

    // Customers
    Route::get('/customers', [SSP\CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/create', [SSP\CustomerController::class, 'create'])->name('customers.create');
    Route::post('/customers', [SSP\CustomerController::class, 'store'])->name('customers.store');
    Route::get('/customers/{customer}/edit', [SSP\CustomerController::class, 'edit'])->name('customers.edit');
    Route::put('/customers/{customer}', [SSP\CustomerController::class, 'update'])->name('customers.update');
    Route::put('/customers/{customer}/rate', [SSP\CustomerController::class, 'updateRate'])->name('customers.rate');
    Route::post('/customers/{customer}/recharge', [SSP\CustomerController::class, 'recharge'])->name('customers.recharge');
    Route::get('/customers/{customer}/login-as', [SSP\CustomerController::class, 'loginAs'])->name('customers.login-as');

    // White Label Settings
    Route::get('/white-label', [SSP\WhiteLabelController::class, 'index'])->name('white-label.index');
    Route::post('/white-label', [SSP\WhiteLabelController::class, 'update'])->name('white-label.update');

    // Mail / SMTP Settings
    Route::get('/mail-settings', [SSP\MailSettingController::class, 'index'])->name('mail-settings.index');
    Route::post('/mail-settings', [SSP\MailSettingController::class, 'update'])->name('mail-settings.update');
    Route::post('/mail-settings/test', [SSP\MailSettingController::class, 'sendTestMail'])->name('mail-settings.test');

    // Payment Gateways
    Route::get('/gateways', [SSP\GatewayController::class, 'index'])->name('gateways.index');
    Route::get('/gateways/create', [SSP\GatewayController::class, 'create'])->name('gateways.create');
    Route::post('/gateways', [SSP\GatewayController::class, 'store'])->name('gateways.store');
    Route::get('/gateways/{gateway}/edit', [SSP\GatewayController::class, 'edit'])->name('gateways.edit');
    Route::put('/gateways/{gateway}', [SSP\GatewayController::class, 'update'])->name('gateways.update');
    Route::delete('/gateways/{gateway}', [SSP\GatewayController::class, 'destroy'])->name('gateways.destroy');

    // Customer Recharge Requests
    Route::get('/recharges', [SSP\RechargeController::class, 'index'])->name('recharges.index');
    Route::post('/recharges/{recharge}/approve', [SSP\RechargeController::class, 'approve'])->name('recharges.approve');
    Route::post('/recharges/{recharge}/reject', [SSP\RechargeController::class, 'reject'])->name('recharges.reject');

    // SSP Own Recharge (from parent SP)
    Route::get('/my-recharge', [SSP\RechargeController::class, 'myRecharge'])->name('my-recharge');
    Route::post('/my-recharge', [SSP\RechargeController::class, 'storeMyRecharge'])->name('my-recharge.store');

    // Reports & Analytics
    Route::get('/reports/profit-loss', [SSP\ProfitLossController::class, 'index'])->name('reports.profit-loss');
    Route::get('/reports/profit-loss/export', [SSP\ProfitLossController::class, 'export'])->name('reports.profit-loss.export');
    Route::get('/reports', [SSP\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [SSP\ReportController::class, 'export'])->name('reports.export');
});
