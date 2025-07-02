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
        // Helper function for Vercel deployment
        if (! function_exists('is_serverless')) {
            function is_serverless()
            {
                return env('VERCEL') !== null;
            }
        }
    }
}