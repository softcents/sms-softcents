<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin as ADMIN;

Route::group(['as' => 'admin.', 'prefix' => 'admin', 'middleware' => ['admin']], function () {
    Route::get('/', [ADMIN\DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/get-dashboard', [Admin\DashboardController::class, 'getDashboardData'])->name('dashboard.data');
    Route::get('/yearly-statistics', [Admin\DashboardController::class, 'yearlyStatistics'])->name('dashboard.statistics');
    Route::get('/users-overview', [ADMIN\DashboardController::class, 'smsOverview'])->name('dashboard.sms-overview');

    Route::get('users/{user}/login-as', [ADMIN\UserController::class, 'loginAs'])->name('users.login-as');
    Route::resource('users', ADMIN\UserController::class);
    Route::post('users/filter', [ADMIN\UserController::class, 'acnooFilter'])->name('users.filter');
    Route::post('users/status/{id}', [ADMIN\UserController::class,'status'])->name('users.status');
    Route::post('users/delete-all', [ADMIN\UserController::class,'deleteAll'])->name('users.delete-all');

    // Recharge
    Route::resource('recharges', ADMIN\RechargeController::class)->except('destroy', 'update', 'create', 'edit');
    Route::post('recharges/status/{id}', [ADMIN\RechargeController::class, 'statusUpdate'])->name('recharge.status');
    Route::post('recharges/filter', [ADMIN\RechargeController::class, 'acnooFilter'])->name('recharges.filter');
    Route::get('recharges/print/{id}', [ADMIN\RechargeController::class, 'print'])->name('recharges.print');

    Route::resource('senderips', ADMIN\AcnooSenderIpController::class)->only('index');

    Route::resource('gateways-types', ADMIN\GatewayTypeController::class);
    Route::resource('sms-gateways', ADMIN\SmsGatewayController::class);
    Route::post('sms-gateways/delete-all', [ADMIN\SmsGatewayController::class,'deleteAll'])->name('sms-gateways.delete-all');
    Route::post('sms-gateways/filter', [ADMIN\SmsGatewayController::class, 'acnooFilter'])->name('sms-gateways.filter');

    Route::resource('plans', ADMIN\AcnooPlanController::class)->except('show');
    Route::post('plans/status/{id}', [ADMIN\AcnooPlanController::class,'status'])->name('plans.status');
    Route::post('plans/delete-all', [ADMIN\AcnooPlanController::class, 'deleteAll'])->name('plans.delete-all');

    Route::resource('subscribers', ADMIN\SubscriberController::class)->only('index', 'show');
    Route::post('subscribers/filter', [ADMIN\SubscriberController::class, 'acnooSubscribeFilter'])->name('subscribers.filter');
    Route::get('subscribers/print/{id}', [ADMIN\SubscriberController::class, 'print'])->name('subscribers.print');
    Route::post('subscription/status/{id}', [ADMIN\SubscriberController::class, 'status'])->name('subscription.status');

    // Campaigns & Campaigns Sms
    Route::resource('campaigns_sms', ADMIN\AcnooCampaignSmsController::class)->only('index', 'store');

    Route::resource('campaigns', ADMIN\AcnooCampaignController::class)->except('show');
    Route::post('campaigns/status/{id}', [ADMIN\AcnooCampaignController::class, 'status'])->name('campaigns.status');
    Route::post('campaigns/delete-all', [ADMIN\AcnooCampaignController::class, 'deleteAll'])->name('campaigns.delete-all');
    Route::post('campaigns/filter', [ADMIN\AcnooCampaignController::class, 'acnooFilter'])->name('campaigns.filter');

    // Sender Ids
    Route::resource('senderids', ADMIN\AcnooSenderController::class)->except('show');
    Route::post('senderids/status/{id}', [ADMIN\AcnooSenderController::class, 'status'])->name('senderids.status');
    Route::post('senderids/delete-all', [ADMIN\AcnooSenderController::class, 'deleteAll'])->name('senderids.delete-all');
    Route::post('senderids/filter', [ADMIN\AcnooSenderController::class, 'acnooFilter'])->name('senderids.filter');

    // Send SMS
    //Route::resource('sms', ADMIN\SmsController::class)->except('show');

    // Currencies
    Route::resource('currencies', ADMIN\AcnooCurrencyController::class)->except('show', 'destroy');
    Route::match(['get', 'post'], 'currencies/default/{id}', [ADMIN\AcnooCurrencyController::class, 'default'])->name('currencies.default');

    Route::resource('profiles', ADMIN\ProfileController::class)->only('index', 'update');

    // Website settings
    Route::resource('website-settings',Admin\AcnooWebSettingController::class);

    Route::resource('newsletters', Admin\AcnooNewsletterController::class)->only('index', 'destroy');
    Route::post('newsletters/delete-all', [ADMIN\AcnooNewsletterController::class, 'deleteAll'])->name('newsletters.delete-all');

    // Faqs Controller
    Route::resource('faqs', Admin\AcnooFaqsController::class);
    Route::post('faqs/status/{id}', [ADMIN\AcnooFaqsController::class,'status'])->name('faqs.status');
    Route::post('faqs/delete-all', [ADMIN\AcnooFaqsController::class, 'deleteAll'])->name('faqs.delete-all');

    // Services Controller
    Route::resource('services', Admin\ServiceController::class);
    Route::post('services/status/{id}', [ADMIN\ServiceController::class,'status'])->name('services.status');
    Route::post('services/delete-all', [ADMIN\ServiceController::class, 'deleteAll'])->name('services.delete-all');

    // Blog Controller
    Route::resource('blogs', Admin\AcnooBlogController::class);
    Route::post('blogs/status/{id}', [ADMIN\AcnooBlogController::class,'status'])->name('blogs.status');
    Route::post('blogs/delete-all', [ADMIN\AcnooBlogController::class, 'deleteAll'])->name('blogs.delete-all');
    Route::get('blogs/comments/{id}', [ADMIN\AcnooBlogController::class, 'filterComment'])->name('blogs.filter.comment');

    //Comment Controller
    Route::resource('comments', Admin\AcnooCommentController::class);
    Route::post('comments/delete-all', [ADMIN\AcnooCommentController::class, 'deleteAll'])->name('comments.delete-all');


    Route::resource('testimonials', ADMIN\AcnooTestimonialController::class)->except('show');
    Route::post('testimonials/delete-all', [ADMIN\AcnooTestimonialController::class, 'deleteAll'])->name('testimonials.delete-all');

    // Roles & Permissions
    Route::resource('roles', ADMIN\RoleController::class)->except('show');
    Route::resource('permissions', ADMIN\PermissionController::class)->only('index', 'store');

    // Settings
    Route::get('cron-jobs', [ADMIN\CronJobController::class, 'index'])->name('cron-jobs.index');
    Route::resource('notice', ADMIN\NoticeController::class)->only('index', 'store');
    Route::resource('settings', ADMIN\SettingController::class)->only('index', 'update');
    Route::resource('sms-settings', ADMIN\AcnooSmsSettingsController::class)->only('index', 'store');
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
});

