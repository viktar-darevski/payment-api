<?php

namespace App\Rules;

use Brick\Math\Exception\NumberFormatException;
use Brick\Math\Exception\RoundingNecessaryException;
use Brick\Money\Exception\UnknownCurrencyException;
use Brick\Money\Money;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ItemCurrencyField implements ValidationRule
{

    public function __construct(private readonly string $currency)
    {
    }


    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            // Validate the money value with the given currency
            Money::of($value, $this->currency);
        } catch (UnknownCurrencyException|NumberFormatException|RoundingNecessaryException $exception){
            // Invoke the fail callback with the error message
            $fail('The :attribute has next error:'. $exception->getMessage());
        }
    }

}
