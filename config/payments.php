<?php

return [

    'providers' => [
        \App\Services\Payments\Providers\StripePaymentProvider::PROVIDER_NAME => [
            'secret' => env('STRIPE_SECRET'),
            'key' => env('STRIPE_KEY'),
        ],
    ],

    'callbacks_prefix' => 'payments-callback',
];
