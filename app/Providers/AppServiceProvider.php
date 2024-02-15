<?php

namespace App\Providers;

use App\Contracts\Repositories\ITransactionRepository;
use App\Contracts\Services\IPaymentsService;
use App\Contracts\Services\ITransactionService;
use App\Repositories\TransactionRepository;
use App\Services\Transaction\TransactionService;
use Illuminate\Support\ServiceProvider;

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

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
