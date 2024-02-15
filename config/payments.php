<?php

return [

    'providers' => [
        \App\Services\Payments\Providers\StripePaymentProvider::PROVIDER_NAME => [
            'secret' => env('STRIPE_SECRET'),
            'key' => env('STRIPE_KEY'),
        ],

        \App\Services\Payments\Providers\PaypalPaymentProvider::PROVIDER_NAME => [
            'secret' => env('PAYPAL_SECRET'),
            'key' => env('PAYPAL_KEY'),
        ],
    ],

    'callbacks_prefix' => 'payments-callback',
];
