<?php

namespace App\Providers;

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
        \Illuminate\Support\Facades\Schema::defaultStringLength(191);
        \App\Models\GoldPrice::observe(\App\Observers\GoldPriceObserver::class);

        // Share branding settings
        if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
            $branding = \App\Models\Setting::where('group', 'branding')->pluck('value', 'key');
            \Illuminate\Support\Facades\View::share('branding', $branding);
        }

        // Force HTTPS in Production (Shared Hosting Fix)
        // TEMPORARILY DISABLED TO STOP REDIRECT LOOP
        /*
        if ($this->app->environment('production')) {
            $this->app['request']->server->set('HTTPS', 'on');
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
        */
    }
}
