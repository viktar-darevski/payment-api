<?php

namespace App\Contracts;

use App\Models\TransactionState;

interface ITransactionState
{
    public function proceed() : void;
    public static function getDatabaseState(): TransactionState;
}
