<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        URL::forceScheme('https');

        view()->composer('*', function ($view) {
            if (!$view->offsetExists('errors')) {
                $view->with('errors', session('errors', new \Illuminate\Support\ViewErrorBag));
            }
        });
    }
}
