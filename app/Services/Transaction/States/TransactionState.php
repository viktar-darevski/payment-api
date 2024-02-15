<?php

namespace App\Services\Transaction\States;

use App\Models\Transaction;

abstract class TransactionState
{
    /**
     * @var Transaction
     */
    protected Transaction $transaction;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    abstract public function proceed();
}
