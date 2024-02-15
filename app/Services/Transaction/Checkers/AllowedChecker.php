<?php

namespace App\Services\Transaction\Checkers;

use App\Models\Transaction;

class AllowedChecker extends BaseChecker implements ICheckerInterface
{
    public function check(Transaction $transaction): bool {
        return true;
    }
}
