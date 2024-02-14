<?php

namespace App\Services\Payments\Providers;

class PaymentSession
{
    public function __construct(
        private readonly string $sessionId,
        private readonly string $paymentUrl,
        private readonly string $provider,
        private string $paymentSecret = "",
    )
    {
    }

    public function getProvider(): string
    {
        return $this->provider;
    }

    public function getSessionId(): string
    {
        return $this->sessionId;
    }

    public function getPaymentUrl(): string
    {
        return $this->paymentUrl;
    }

    public function getPaymentSecret(): string
    {
        return $this->paymentSecret;
    }

    public function setPaymentSecret(string $paymentSecret): void
    {
        $this->paymentSecret = $paymentSecret;
    }

}
