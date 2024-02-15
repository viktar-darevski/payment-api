<?php

namespace App\Services\Payments\Providers;

class PaymentCallback
{
    public function __construct(
        private readonly string $callbackUrl,
        private readonly string $sessionCode,
        private readonly string $status,
    )
    {
    }

    public function getCallbackUrl(): string
    {
        return $this->callbackUrl;
    }

    public function getSessionCode(): string
    {
        return $this->sessionCode;
    }

    public function getStatus(): string
    {
        return $this->status;
    }


    public function generateLink(): string
    {
        return $this->callbackUrl . '?sessionCode=' . urlencode($this->sessionCode) . '&status=' . urlencode($this->status);
    }

}
