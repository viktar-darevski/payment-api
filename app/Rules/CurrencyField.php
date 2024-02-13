<?php

namespace App\Rules;

use Brick\Money\Exception\UnknownCurrencyException;
use Brick\Money\ISOCurrencyProvider;
use Brick\Money\Money;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

use Illuminate\Support\Facades\App;

class CurrencyField implements ValidationRule
{

    public function __construct(protected readonly ISOCurrencyProvider $currencyProvider)
    {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            $this->currencyProvider->getCurrency($value);
        } catch (UnknownCurrencyException $exception){
            $fail('The :attribute has next error:'. $exception->getMessage());
        }
    }

}
