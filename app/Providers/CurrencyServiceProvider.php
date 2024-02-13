<?php

namespace App\Providers;

use Brick\Money\ISOCurrencyProvider;
use Illuminate\Support\ServiceProvider;

class CurrencyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(ISOCurrencyProvider::class, function ($app) {
            return ISOCurrencyProvider::getInstance();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
