<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CronSmsStatusController;
use App\Http\Controllers\CronScheduleSmsController;

// Schedule messages cron jobs.
Route::group(['prefix' => 'cron'], function() {

    Route::get('sms-status', [CronSmsStatusController::class, 'index'])->name('cron.sms-status');
    Route::get('schedule-messages', [CronScheduleSmsController::class, 'scheduleMessage'])->name('cron.schedule-messages');
    Route::get('low-blanace-notify', [CronScheduleSmsController::class, 'lowBalanceNotify'])->name('cron.low-blanace-notify');

});
