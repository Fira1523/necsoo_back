<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Schema;
use Illuminate\Auth\Notifications\ResetPassword;

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
        //
         Schema::defaultStringLength(191);
         ResetPassword::createUrlUsing(function ($notifiable, $token) {
        return 'http://localhost:3000/reset-password?token=' . $token . '&email=' . urlencode($notifiable->email);
    });
    }
}
