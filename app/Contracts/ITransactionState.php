<?php

namespace App\Contracts;

use App\Models\Transaction;
use App\Models\TransactionState;

interface ITransactionState
{
    public function process(Transaction $transaction): void;
    public function getDatabaseState(): TransactionState;
    public function getName() : string;
}
