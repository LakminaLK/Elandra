<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        // Force HTTPS URLs when APP_URL is HTTPS (for ngrok)
        if (str_starts_with(config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }
        
        // Force HTTPS for asset URLs when using ngrok
        if (str_contains(config('app.url'), 'ngrok')) {
            URL::forceScheme('https');
            $this->app['request']->server->set('HTTPS', 'on');
        }
    }
}
