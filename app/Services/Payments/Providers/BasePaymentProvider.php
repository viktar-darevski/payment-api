<?php

namespace App\Services\Payments\Providers;

use Exception;

abstract class BasePaymentProvider implements IPaymentProvider
{
    protected string|null $successUrl;
    protected string|null $cancelUrl;
    protected string|null $failedUrl;
    protected string|null $locale = 'en';

    protected string $name;


    public function name(): string {
        return $this->name;
    }

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function getSuccessUrl(): ?string
    {
        return $this->successUrl;
    }

    public function setSuccessUrl(?string $successUrl): void
    {
        $this->successUrl = $successUrl;
    }

    public function getCancelUrl(): ?string
    {
        return $this->cancelUrl;
    }

    public function setCancelUrl(?string $cancelUrl): void
    {
        $this->cancelUrl = $cancelUrl;
    }


    public function __construct($sessionSecret){
        $this->setSuccessUrl(
            route('payments.callback.success', ['sessionSecret' => $sessionSecret , 'provider' => $this->name()])
        );

        $this->setCancelUrl(
            route('payments.callback.cancel', ['sessionSecret' => $sessionSecret , 'provider' => $this->name()])
        );
    }

}
