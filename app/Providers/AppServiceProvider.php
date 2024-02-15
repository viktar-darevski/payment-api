<?php

namespace App\Providers;

use App\Contracts\Repositories\ITransactionRepository;
use App\Contracts\Services\IPaymentsService;
use App\Contracts\Services\ITransactionService;
use App\Repositories\TransactionRepository;
use App\Services\Payments\Providers\PaymentException;
use App\Services\Transaction\TransactionService;
use Illuminate\Support\ServiceProvider;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use Stripe\StripeClient;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ITransactionRepository::class, function ($app) {
            return new TransactionRepository();
        });

        $this->app->bind(ITransactionService::class, function($app) {
            return new TransactionService(
                $app->make(ITransactionRepository::class),
                $app->make(IPaymentsService::class)
            );
        });

        $this->app->bind(StripeClient::class, function ($app) {
            $secretKey = config('payments.providers.stripe.secret');

            if (!$secretKey) {
                throw new PaymentException('Stripe secret key is not set');
            }

            return new StripeClient($secretKey);
        });

        $this->app->bind(PayPalHttpClient::class, function ($app) {
            $key = config('payments.providers.paypal.key');
            $secret = config('payments.providers.paypal.secret');
            if (!$key || !$secret) {
                throw new PaymentException('PayPal client ID or secret is not set');
            }
            $environment = new SandboxEnvironment($key, $secret);
            return new PayPalHttpClient($environment);

        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
