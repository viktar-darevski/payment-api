<?php

namespace App\Services\DataModels;

use Brick\Money\Money;

class PaymentDataItemModel
{
    public function __construct(
        private readonly string $name,
        private readonly string $description,
        private readonly int $quantity,
        private readonly Money $value
    ) {
        //
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getValue(): Money
    {
        return $this->value;
    }
}
