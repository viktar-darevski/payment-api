<?php

namespace App\Rules;

use App\Services\Payments\Providers\PaymentFactory;
use Brick\Money\Exception\UnknownCurrencyException;
use Brick\Money\ISOCurrencyProvider;
use Brick\Money\Money;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

use Illuminate\Support\Facades\App;

class PaymentProviderRule implements ValidationRule
{

    public function __construct(protected readonly ISOCurrencyProvider $currencyProvider)
    {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $availableProviders = PaymentFactory::getAvailableProviders();
        if (!in_array($value, $availableProviders)) {
            $fail('The :attribute is not an available payment provider');
        }
    }

}
