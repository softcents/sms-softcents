<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User as User;
use App\Http\Controllers\User\SoftcentsDeveloperApiController;

// Recharge Routes
Route::group(['middleware' => ['auth']], function () {
    Route::get('/recharge-gateways', [User\RechargePaymentController::class, 'index'])->name('recharges-gateways.index');
    Route::post('/recharge/{gateway_id}', [User\RechargePaymentController::class, 'recharge'])->name('recharges-gateways.payment');
    Route::get('/users/recharge/success', [User\RechargePaymentController::class, 'success'])->name('recharge.success');
    Route::get('/users/recharge/failed', [User\RechargePaymentController::class, 'failed'])->name('recharge.failed');
    Route::post('ssl-commerz/recharge/success', [User\RechargePaymentController::class, 'sslCommerzSuccess']);
    Route::post('ssl-commerz/recharge/failed', [User\RechargePaymentController::class, 'sslCommerzFailed']);
});

Route::group([
    'prefix' => 'users',
    'as' => 'users.',
    'middleware' => ['auth'],
    'namespace' => 'App\Library',
], function () {
    Route::get('/payment/paypal', 'Paypal@status');
    Route::get('/payment/mollie', 'Mollie@status');
    Route::post('/payment/paystack', 'Paystack@status')->name('paystack.status');
    Route::get('/paystack', 'Paystack@view')->name('paystack.view');
    Route::get('/razorpay/payment', 'Razorpay@view')->name('razorpay.view');
    Route::post('/razorpay/status', 'Razorpay@status');
    Route::get('/mercadopago/pay', 'Mercado@status')->name('mercadopago.status');
    Route::get('/payment/flutterwave', 'Flutterwave@status');
    Route::get('/payment/thawani', 'Thawani@status');
    Route::get('/payment/instamojo', 'Instamojo@status');
    Route::get('/payment/toyyibpay', 'Toyyibpay@status');
    Route::get('/manual/payment', 'CustomGateway@status')->name('manual.payment');
    Route::get('payu/payment', 'Payu@view')->name('payu.view');
    Route::post('/payu/status', 'Payu@status')->name('payu.status');
    Route::post('/phonepe/status', 'PhonePe@status');
    Route::post('/paytm/status', 'Paytm@status');
    Route::get('/tap-payment/status', 'TapPayment@status')->name('tap-payment.status');
    Route::get('/pipra-pay/status', 'PipraPay@status')->name('pipra-pay.status');
});

Route::get('/customer', function() { return redirect()->route('users.dashboard.index'); });
Route::get('/customer/dashboard', function() { return redirect()->route('users.dashboard.index'); });
Route::get('/superadmin', function() { return redirect()->route('admin.dashboard.index'); });

Route::group(['as' => 'users.', 'prefix' => 'users', 'middleware' => ['users']], function () {

    Route::get('dashboard', [User\DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/get-dashboard', [User\DashboardController::class, 'getDashboardData'])->name('dashboard.data');
    Route::get('/yearly-statistics', [User\DashboardController::class, 'yearlyStatistics'])->name('dashboard.statistics');
    Route::get('/users-overview', [User\DashboardController::class, 'smsOverview'])->name('dashboard.sms-overview');

    Route::resource('profiles', User\ProfileController::class)->only('index', 'update');

    Route::resource('quick-sms', User\QuickSmsController::class)->only('index', 'store');
    Route::resource('group-sms', User\SoftcentsGroupSmsController::class)->only('index', 'store');
    Route::resource('bulk-sms', User\BulkSmsController::class)->only('index', 'store');

    Route::resource('senderids', User\SenderIdController::class)->only('index');
    Route::post('senderids/filter', [User\SenderIdController::class, 'softcentsFilter'])->name('senderids.filter');

    // Campaigns
    Route::resource('campaigns', User\SoftcentsCampaignController::class);
    Route::post('campaigns/filter', [User\SoftcentsCampaignController::class, 'softcentsFilter'])->name('campaigns.filter');

    // Recharge
    Route::resource('recharges', User\RechargeController::class)->only('index', 'store', 'show');
    Route::post('recharges/filter', [User\RechargeController::class, 'softcentsFilter'])->name('recharges.filter');

    // Plans
    Route::resource('plans', User\PlanController::class)->only('index');

    Route::resource('subscribers', User\SubscriberController::class)->only('index', 'show');
    Route::post('subscribers/filter', [User\SubscriberController::class, 'softcentsFilter'])->name('subscribers.filter');

    // Contacts & Groups
    Route::resource('groups', User\GroupController::class)->except('create', 'show');
    Route::post('groups/delete-all', [User\GroupController::class,'deleteAll'])->name('groups.delete-all');
    Route::post('groups/filter', [User\GroupController::class, 'softcentsFilter'])->name('groups.filter');

    Route::resource('contacts', User\ContactController::class)->except('show');
    Route::resource('bulk-contacts', User\BulkContactController::class)->only('index', 'store');
    Route::post('contacts/delete-all', [User\ContactController::class,'deleteAll'])->name('contacts.delete-all');
    Route::post('contacts/filter', [User\ContactController::class, 'softcentsFilter'])->name('contacts.filter');

    // SMS Templates
    Route::resource('templates', User\SoftcentsTemplateController::class)->except('create', 'show', 'edit');
    Route::post('templates/filter', [User\SoftcentsTemplateController::class, 'softcentsFilter'])->name('templates.filter');

    Route::get('get-templates', [User\SoftcentsTemplateController::class, 'getTemplates'])->name('get-templates');
    Route::post('templates/delete-all', [User\SoftcentsTemplateController::class,'deleteAll'])->name('templates.delete-all');

    // Developer options and API Keys
    Route::resource('client-secret', User\SoftcentsDeveloperApiController::class)->only('index', 'store');
    Route::resource('senderips', User\UserSenderIpController::class)->only('index', 'store', 'destroy');
    Route::post('senderips/status/{id}', [User\UserSenderIpController::class, 'status'])->name('senderips.status');
    Route::get('/developer-options', [SoftcentsDeveloperApiController::class, 'developerOptions'])->name('developer-options');

    Route::group(['as' => 'reports.', 'prefix' => 'reports'], function () {

        Route::get('/expenses', [User\ExpenseReportController::class, 'index'])->name('expenses');
        Route::get('/expenses/export', [User\ExpenseReportController::class, 'export'])->name('expenses.export');
        Route::get('/analytics', [User\AnalyticsReportController::class, 'index'])->name('analytics');
        Route::get('/analytics/export', [User\AnalyticsReportController::class, 'export'])->name('analytics.export');
        Route::get('/quick-sms', [User\ReportController::class, 'quickSms'])->name('quick-sms');
        Route::get('/group-sms', [User\ReportController::class, 'groupSms'])->name('group-sms');
        Route::get('/campaigns-sms', [User\ReportController::class, 'campaigns'])->name('campaigns-sms');
        Route::get('/subscriptions-sms', [User\ReportController::class, 'subscriptions'])->name('subscriptions');
        Route::get('/recharges-sms', [User\ReportController::class, 'recharges'])->name('recharges');
        Route::get('/transactions', [User\ReportController::class, 'transactions'])->name('transactions');

    });

    // Live Queue Monitor
    Route::get('/queue-monitor', [User\QueueMonitorController::class, 'index'])->name('queue-monitor.index');
    Route::get('/queue-monitor/data', [User\QueueMonitorController::class, 'liveData'])->name('queue-monitor.data');
    Route::post('/queue-monitor/cancel-sms/{id}', [User\QueueMonitorController::class, 'cancelSms'])->name('queue-monitor.cancel-sms');
    Route::post('/queue-monitor/cancel-pending', [User\QueueMonitorController::class, 'cancelAllPendingSms'])->name('queue-monitor.cancel-pending');
    Route::post('/queue-monitor/retry-sms/{id}', [User\QueueMonitorController::class, 'retrySms'])->name('queue-monitor.retry-sms');
    Route::post('/queue-monitor/retry-failed', [User\QueueMonitorController::class, 'retryAllFailedSms'])->name('queue-monitor.retry-failed');
    Route::post('/queue-monitor/cancel-campaign/{id}', [User\QueueMonitorController::class, 'cancelCampaignPending'])->name('queue-monitor.cancel-campaign');

    Route::get('download-contacts-file', function() {
        $filePath = public_path('assets/contacts.xlsx');
        if (file_exists($filePath)) {
            return response()->download($filePath);
        } else {
            return redirect(route('users.bulk-contacts.index'))->with('error', 'File not found.');
        }
    })->name('download-contacts-file');

    Route::get('download-numbers-file', function() {
        $filePath = public_path('assets/numbers.xlsx');
        if (file_exists($filePath)) {
            return response()->download($filePath);
        } else {
            return redirect(route('users.bulk-numbers.index'))->with('error', 'File not found.');
        }
    })->name('download-numbers-file');
});

Route::match(['get', 'post'], 'send-message', [User\SoftcentsSmsApiController::class, 'index'])->name('send-message.index');
Route::match(['get', 'post'], 'check-balance', [User\BalanceCheckController::class, 'index'])->name('check-balance.index');
