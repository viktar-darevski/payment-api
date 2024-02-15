<?php

namespace App\Services\Transaction\Checkers;

use App\Models\Transaction;

abstract class BaseChecker
{
    abstract public function check(Transaction $transaction): bool;
}
