<?php

namespace App\Providers;

use App\Contracts\Services\IPaymentsService;
use App\Services\Payments\PaymentsService;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{

    public function boot(): void
    {

    }

    public function register() : void
    {
        $this->app->bind(IPaymentsService::class, function() {
            return new PaymentsService();
        });
    }
}
