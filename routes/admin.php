<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin as ADMIN;

Route::group(['as' => 'admin.', 'prefix' => 'admin', 'middleware' => ['admin']], function () {
    Route::get('/', [ADMIN\DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/get-dashboard', [Admin\DashboardController::class, 'getDashboardData'])->name('dashboard.data');
    Route::get('/yearly-statistics', [Admin\DashboardController::class, 'yearlyStatistics'])->name('dashboard.statistics');
    Route::get('/users-overview', [ADMIN\DashboardController::class, 'smsOverview'])->name('dashboard.sms-overview');

    Route::get('users/{user}/login-as', [ADMIN\UserController::class, 'loginAs'])->name('users.login-as');
    Route::post('users/recharge', [ADMIN\UserController::class, 'recharge'])->name('users.recharge');
    Route::post('users/{user}/recharge', [ADMIN\UserController::class, 'recharge'])->name('users.recharge.user');
    Route::post('users/tariff', [ADMIN\UserController::class, 'updateTariff'])->name('users.tariff.update');
    Route::post('users/{user}/tariff', [ADMIN\UserController::class, 'updateTariff'])->name('users.tariff.update.user');
    Route::get('tariffs', [ADMIN\TariffController::class, 'index'])->name('tariffs.index');
    Route::put('tariffs/{user}', [ADMIN\TariffController::class, 'update'])->name('tariffs.update');
    Route::post('users/assign-plan', [ADMIN\UserController::class, 'assignPlan'])->name('users.assign-plan');
    Route::post('users/{user}/assign-plan', [ADMIN\UserController::class, 'assignPlan'])->name('users.assign-plan.user');
    Route::resource('users', ADMIN\UserController::class);
    Route::post('users/filter', [ADMIN\UserController::class, 'softcentsFilter'])->name('users.filter');
    Route::post('users/status/{id}', [ADMIN\UserController::class,'status'])->name('users.status');
    Route::post('users/delete-all', [ADMIN\UserController::class,'deleteAll'])->name('users.delete-all');

    // Recharge
    Route::resource('recharges', ADMIN\RechargeController::class)->except('destroy', 'update', 'create', 'edit');
    Route::post('recharges/status/{id}', [ADMIN\RechargeController::class, 'statusUpdate'])->name('recharge.status');
    Route::post('recharges/filter', [ADMIN\RechargeController::class, 'softcentsFilter'])->name('recharges.filter');
    Route::get('recharges/print/{id}', [ADMIN\RechargeController::class, 'print'])->name('recharges.print');

    Route::resource('senderips', ADMIN\SoftcentsSenderIpController::class)->only(['index', 'store', 'destroy']);
    Route::post('senderips/status/{id}', [ADMIN\SoftcentsSenderIpController::class, 'status'])->name('senderips.status');
    Route::post('senderips/delete-all', [ADMIN\SoftcentsSenderIpController::class, 'deleteAll'])->name('senderips.delete-all');

    Route::resource('gateways-types', ADMIN\GatewayTypeController::class);
    Route::get('sms-gateways/balances/all', [ADMIN\SmsGatewayController::class, 'checkAllBalances'])->name('sms-gateways.all-balances');
    Route::get('sms-gateways/{id}/balance', [ADMIN\SmsGatewayController::class, 'checkBalance'])->name('sms-gateways.balance');
    Route::resource('sms-gateways', ADMIN\SmsGatewayController::class);
    Route::post('sms-gateways/delete-all', [ADMIN\SmsGatewayController::class,'deleteAll'])->name('sms-gateways.delete-all');
    Route::post('sms-gateways/filter', [ADMIN\SmsGatewayController::class, 'softcentsFilter'])->name('sms-gateways.filter');

    Route::resource('plans', ADMIN\SoftcentsPlanController::class)->except('show');
    Route::post('plans/status/{id}', [ADMIN\SoftcentsPlanController::class,'status'])->name('plans.status');
    Route::post('plans/delete-all', [ADMIN\SoftcentsPlanController::class, 'deleteAll'])->name('plans.delete-all');

    Route::resource('subscribers', ADMIN\SubscriberController::class)->only('index', 'show');
    Route::post('subscribers/filter', [ADMIN\SubscriberController::class, 'softcentsSubscribeFilter'])->name('subscribers.filter');
    Route::get('subscribers/print/{id}', [ADMIN\SubscriberController::class, 'print'])->name('subscribers.print');
    Route::post('subscription/status/{id}', [ADMIN\SubscriberController::class, 'status'])->name('subscription.status');

    // Campaigns & Campaigns Sms
    Route::resource('campaigns_sms', ADMIN\SoftcentsCampaignSmsController::class)->only('index', 'store');

    Route::resource('campaigns', ADMIN\SoftcentsCampaignController::class)->except('show');
    Route::post('campaigns/status/{id}', [ADMIN\SoftcentsCampaignController::class, 'status'])->name('campaigns.status');
    Route::post('campaigns/delete-all', [ADMIN\SoftcentsCampaignController::class, 'deleteAll'])->name('campaigns.delete-all');
    Route::post('campaigns/filter', [ADMIN\SoftcentsCampaignController::class, 'softcentsFilter'])->name('campaigns.filter');

    // Sender Ids
    Route::resource('senderids', ADMIN\SoftcentsSenderController::class)->except('show');
    Route::post('senderids/status/{id}', [ADMIN\SoftcentsSenderController::class, 'status'])->name('senderids.status');
    Route::post('senderids/delete-all', [ADMIN\SoftcentsSenderController::class, 'deleteAll'])->name('senderids.delete-all');
    Route::post('senderids/filter', [ADMIN\SoftcentsSenderController::class, 'softcentsFilter'])->name('senderids.filter');
    Route::post('senderids/assign-gateway', [ADMIN\SoftcentsSenderController::class, 'assignGateway'])->name('senderids.assign-gateway');

    // Send SMS
    //Route::resource('sms', ADMIN\SmsController::class)->except('show');

    // Currencies
    Route::resource('currencies', ADMIN\SoftcentsCurrencyController::class)->except('show', 'destroy');
    Route::match(['get', 'post'], 'currencies/default/{id}', [ADMIN\SoftcentsCurrencyController::class, 'default'])->name('currencies.default');

    Route::resource('profiles', ADMIN\ProfileController::class)->only('index', 'update');

    // Website settings
    Route::resource('website-settings',Admin\SoftcentsWebSettingController::class);

    Route::resource('newsletters', Admin\SoftcentsNewsletterController::class)->only('index', 'destroy');
    Route::post('newsletters/delete-all', [ADMIN\SoftcentsNewsletterController::class, 'deleteAll'])->name('newsletters.delete-all');

    // Faqs Controller
    Route::resource('faqs', Admin\SoftcentsFaqsController::class);
    Route::post('faqs/status/{id}', [ADMIN\SoftcentsFaqsController::class,'status'])->name('faqs.status');
    Route::post('faqs/delete-all', [ADMIN\SoftcentsFaqsController::class, 'deleteAll'])->name('faqs.delete-all');

    // Services Controller
    Route::resource('services', Admin\ServiceController::class);
    Route::post('services/status/{id}', [ADMIN\ServiceController::class,'status'])->name('services.status');
    Route::post('services/delete-all', [ADMIN\ServiceController::class, 'deleteAll'])->name('services.delete-all');

    // Blog Controller
    Route::resource('blogs', Admin\SoftcentsBlogController::class);
    Route::post('blogs/status/{id}', [ADMIN\SoftcentsBlogController::class,'status'])->name('blogs.status');
    Route::post('blogs/delete-all', [ADMIN\SoftcentsBlogController::class, 'deleteAll'])->name('blogs.delete-all');
    Route::get('blogs/comments/{id}', [ADMIN\SoftcentsBlogController::class, 'filterComment'])->name('blogs.filter.comment');

    //Comment Controller
    Route::resource('comments', Admin\SoftcentsCommentController::class);
    Route::post('comments/delete-all', [ADMIN\SoftcentsCommentController::class, 'deleteAll'])->name('comments.delete-all');


    Route::resource('testimonials', ADMIN\SoftcentsTestimonialController::class)->except('show');
    Route::post('testimonials/delete-all', [ADMIN\SoftcentsTestimonialController::class, 'deleteAll'])->name('testimonials.delete-all');

    // Roles & Permissions
    Route::resource('roles', ADMIN\RoleController::class)->except('show');
    Route::resource('permissions', ADMIN\PermissionController::class)->only('index', 'store');

    // Settings
    Route::get('cron-jobs', [ADMIN\CronJobController::class, 'index'])->name('cron-jobs.index');
    Route::resource('notice', ADMIN\NoticeController::class)->only('index', 'store');
    Route::resource('settings', ADMIN\SettingController::class)->only('index', 'update');
    Route::resource('sms-settings', ADMIN\SoftcentsSmsSettingsController::class)->only('index', 'store');
    Route::resource('system-settings', ADMIN\SystemSettingController::class)->only('index', 'store');

    // Payment Gateway
    Route::resource('gateways', ADMIN\GatewayController::class)->only('index', 'update');

    Route::group(['as' => 'reports.', 'prefix' => 'reports'], function () {

        Route::get('/profit-loss', [ADMIN\ProfitLossController::class, 'index'])->name('profit-loss');
        Route::get('/profit-loss/export', [ADMIN\ProfitLossController::class, 'export'])->name('profit-loss.export');
        Route::get('/advanced', [ADMIN\AdvancedReportController::class, 'index'])->name('advanced');
        Route::get('/advanced/export', [ADMIN\AdvancedReportController::class, 'export'])->name('advanced.export');
        Route::get('/quick-sms', [ADMIN\ReportController::class, 'quickSms'])->name('quick-sms');
        Route::get('/group-sms', [ADMIN\ReportController::class, 'groupSms'])->name('group-sms');
        Route::get('/campaigns-sms', [ADMIN\ReportController::class, 'campaigns'])->name('campaigns-sms');
        Route::get('/subscriptions-sms', [ADMIN\ReportController::class, 'subscriptions'])->name('subscriptions');
        Route::get('/recharges-sms', [ADMIN\ReportController::class, 'recharges'])->name('recharges');
        Route::get('/transactions', [ADMIN\ReportController::class, 'transactions'])->name('transactions');

    });

    // Notifications manager
    Route::prefix('notifications')->controller(ADMIN\NotificationController::class)->name('notifications.')->group(function () {
        Route::get('/', 'mtIndex')->name('index');
        Route::get('/{id}', 'mtView')->name('mtView');
        Route::get('view/all/', 'mtReadAll')->name('mtReadAll');
    });

    // Live SMS Queue Monitor
    Route::group(['as' => 'queue-monitor.', 'prefix' => 'queue-monitor'], function () {
        Route::get('/', [ADMIN\QueueMonitorController::class, 'index'])->name('index');
        Route::get('/data', [ADMIN\QueueMonitorController::class, 'liveData'])->name('data');
        Route::post('/retry-failed/{id?}', [ADMIN\QueueMonitorController::class, 'retryFailed'])->name('retry-failed');
        Route::post('/flush-failed', [ADMIN\QueueMonitorController::class, 'flushFailed'])->name('flush-failed');
        Route::post('/purge/{queue}', [ADMIN\QueueMonitorController::class, 'purgeQueue'])->name('purge');
        Route::post('/restart-workers', [ADMIN\QueueMonitorController::class, 'restartWorkers'])->name('restart-workers');
        Route::get('/inspect/{id}', [ADMIN\QueueMonitorController::class, 'inspectUser'])->name('inspect-user');
        Route::get('/customer-queue/{id}', [ADMIN\QueueMonitorController::class, 'customerQueueDetails'])->name('customer-queue');
        Route::post('/sms/cancel/{id}', [ADMIN\QueueMonitorController::class, 'cancelSms'])->name('sms.cancel');
        Route::post('/sms/retry/{id}', [ADMIN\QueueMonitorController::class, 'retrySms'])->name('sms.retry');
    });

    // SMS Inbox (Two-Way Messaging)
    Route::group(['as' => 'inbox.', 'prefix' => 'inbox'], function () {
        Route::get('/', [\App\Http\Controllers\Admin\InboxController::class, 'index'])->name('index');
        Route::post('/{id}/read', [\App\Http\Controllers\Admin\InboxController::class, 'markAsRead'])->name('read');
        Route::post('/mark-all-read', [\App\Http\Controllers\Admin\InboxController::class, 'markAllRead'])->name('mark-all-read');
        Route::delete('/{id}', [\App\Http\Controllers\Admin\InboxController::class, 'destroy'])->name('destroy');
        Route::post('/sync-anbernet', [\App\Http\Controllers\Admin\InboxController::class, 'syncAnbernet'])->name('sync-anbernet');
    });
});

