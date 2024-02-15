<?php

namespace App\Services\Transaction\Checkers;

use App\Models\Transaction;

class CheckIsSuccessCreatedPayment extends BaseChecker
{

    public function check(Transaction $transaction): bool
    {
        if ($transaction->provider_secret_code && $transaction->provider_session_id) {
            return true;
        }

        return false;
    }
}
