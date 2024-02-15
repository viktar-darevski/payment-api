<?php

namespace App\Models;

use Brick\Money\Money;

class TransactionItem
{
    public string $name;
    public string $description;
    public int $quantity;
    public Money $amount;

    public function __construct(
        string $name, string $description, int $quantity, Money $amount)
    {
        $this->name = $name;
        $this->description = $description;
        $this->quantity = $quantity;
        $this->amount = $amount;
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

    public function getAmount(): Money
    {
        return $this->amount;
    }

}

