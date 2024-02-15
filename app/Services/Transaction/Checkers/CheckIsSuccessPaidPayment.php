<?php

namespace App\Services\Transaction\Checkers;

use App\Models\Transaction;

class CheckIsSuccessPaidPayment extends BaseChecker
{

    public function check(Transaction $transaction): bool
    {
        return true;
    }
}
