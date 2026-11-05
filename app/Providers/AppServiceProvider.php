<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        try {
            $senderName = brand_sender_name();
            $senderEmail = brand_email();
            if ($senderName !== '') {
                config(['mail.from.name' => $senderName]);
            }
            if ($senderEmail !== '') {
                config(['mail.from.address' => $senderEmail]);
            }
        } catch (\Throwable $e) {
            // Safe fallback before migrations run
        }
    }
}
