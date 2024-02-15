<?php

namespace App\Services\Transaction\Checkers;

use App\Models\Transaction;

interface ICheckerInterface
{
    // should be context with transaction in it, but for now it's ok to
    // use Transaction directly, just for example of realization
    public function check(Transaction $transaction): bool;
}
