<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SP as SP;

Route::group(['as' => 'sp.', 'prefix' => 'sp', 'middleware' => ['auth', 'sp']], function () {
    Route::get('/', [SP\DashboardController::class, 'index'])->name('dashboard.root');
    Route::get('/dashboard', [SP\DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/dashboard/data', [SP\DashboardController::class, 'getDashboardData'])->name('dashboard.data');
    Route::get('/dashboard/sms-overview', [SP\DashboardController::class, 'smsOverview'])->name('dashboard.sms-overview');
    Route::get('/dashboard/statistics', [SP\DashboardController::class, 'yearlyStatistics'])->name('dashboard.statistics');

    // Sub Resellers (SSP)
    Route::get('/sub-resellers', [SP\SSPController::class, 'index'])->name('ssps.index');
    Route::get('/sub-resellers/create', [SP\SSPController::class, 'create'])->name('ssps.create');
    Route::post('/sub-resellers', [SP\SSPController::class, 'store'])->name('ssps.store');
    Route::get('/sub-resellers/{ssp}/edit', [SP\SSPController::class, 'edit'])->name('ssps.edit');
    Route::put('/sub-resellers/{ssp}', [SP\SSPController::class, 'update'])->name('ssps.update');
    Route::put('/sub-resellers/{ssp}/rate', [SP\SSPController::class, 'updateRate'])->name('ssps.rate');
    Route::post('/sub-resellers/{ssp}/recharge', [SP\SSPController::class, 'recharge'])->name('ssps.recharge');
    Route::get('/sub-resellers/{ssp}/login-as', [SP\SSPController::class, 'loginAs'])->name('ssps.login-as');

    // Customers
    Route::get('/customers', [SP\CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/create', [SP\CustomerController::class, 'create'])->name('customers.create');
    Route::post('/customers', [SP\CustomerController::class, 'store'])->name('customers.store');
    Route::get('/customers/{customer}/edit', [SP\CustomerController::class, 'edit'])->name('customers.edit');
    Route::put('/customers/{customer}', [SP\CustomerController::class, 'update'])->name('customers.update');
    Route::put('/customers/{customer}/rate', [SP\CustomerController::class, 'updateRate'])->name('customers.rate');
    Route::post('/customers/{customer}/recharge', [SP\CustomerController::class, 'recharge'])->name('customers.recharge');
    Route::get('/customers/{customer}/login-as', [SP\CustomerController::class, 'loginAs'])->name('customers.login-as');

    // White Label Settings
    Route::get('/white-label', [SP\WhiteLabelController::class, 'index'])->name('white-label.index');
    Route::post('/white-label', [SP\WhiteLabelController::class, 'update'])->name('white-label.update');

    // Mail / SMTP Settings
    Route::get('/mail-settings', [SP\MailSettingController::class, 'index'])->name('mail-settings.index');
    Route::post('/mail-settings', [SP\MailSettingController::class, 'update'])->name('mail-settings.update');
    Route::post('/mail-settings/test', [SP\MailSettingController::class, 'sendTestMail'])->name('mail-settings.test');

    // Payment Gateways
    Route::get('/gateways', [SP\GatewayController::class, 'index'])->name('gateways.index');
    Route::get('/gateways/create', [SP\GatewayController::class, 'create'])->name('gateways.create');
    Route::post('/gateways', [SP\GatewayController::class, 'store'])->name('gateways.store');
    Route::get('/gateways/{gateway}/edit', [SP\GatewayController::class, 'edit'])->name('gateways.edit');
    Route::put('/gateways/{gateway}', [SP\GatewayController::class, 'update'])->name('gateways.update');
    Route::delete('/gateways/{gateway}', [SP\GatewayController::class, 'destroy'])->name('gateways.destroy');

    // Customer Recharge Requests
    Route::get('/recharges', [SP\RechargeController::class, 'index'])->name('recharges.index');
    Route::post('/recharges/{recharge}/approve', [SP\RechargeController::class, 'approve'])->name('recharges.approve');
    Route::post('/recharges/{recharge}/reject', [SP\RechargeController::class, 'reject'])->name('recharges.reject');

    // SP Own Recharge (from Super Admin)
    Route::get('/my-recharge', [SP\RechargeController::class, 'myRecharge'])->name('my-recharge');
    Route::post('/my-recharge', [SP\RechargeController::class, 'storeMyRecharge'])->name('my-recharge.store');

    // Reports & Analytics
    Route::get('/reports/profit-loss', [SP\ProfitLossController::class, 'index'])->name('reports.profit-loss');
    Route::get('/reports/profit-loss/export', [SP\ProfitLossController::class, 'export'])->name('reports.profit-loss.export');
    Route::get('/reports', [SP\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [SP\ReportController::class, 'export'])->name('reports.export');

    // Live Queue Monitor
    Route::get('/queue-monitor', [SP\QueueMonitorController::class, 'index'])->name('queue-monitor.index');
    Route::get('/queue-monitor/data', [SP\QueueMonitorController::class, 'liveData'])->name('queue-monitor.data');
    Route::get('/queue-monitor/inspect/{id}', [SP\QueueMonitorController::class, 'inspectUser'])->name('queue-monitor.inspect-user');
    Route::get('/queue-monitor/customer-queue/{id}', [SP\QueueMonitorController::class, 'customerQueueDetails'])->name('queue-monitor.customer-queue');
    Route::post('/queue-monitor/sms/cancel/{id}', [SP\QueueMonitorController::class, 'cancelSms'])->name('queue-monitor.sms.cancel');
    Route::post('/queue-monitor/sms/retry/{id}', [SP\QueueMonitorController::class, 'retrySms'])->name('queue-monitor.sms.retry');
});
