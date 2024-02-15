<?php

namespace App\Services\Payments\Providers;

class PaymentFactory
{
    /**
     * @param string $providerName
     * @param string $paymentSecret
     * @return IPaymentProvider
     * @throws PaymentException
     */
    public static function create(string $providerName, string $paymentSecret, string $paymentID): IPaymentProvider{

        return match ($providerName) {
            PaypalPaymentProvider::PROVIDER_NAME => new PaypalPaymentProvider($paymentSecret, $paymentID),
            StripePaymentProvider::PROVIDER_NAME => new StripePaymentProvider($paymentSecret, $paymentID),
            default => throw new \Exception('Invalid payment provider name'),
        };
    }

    /**
     * @return array
     */
    public static function getAvailableProviders(): array {
        return [
            PaypalPaymentProvider::PROVIDER_NAME,
            StripePaymentProvider::PROVIDER_NAME
        ];
    }
}
