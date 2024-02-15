<?php

namespace App\Services\Transaction\States;

use App\Contracts\ITransactionState;

use App\Models\TransactionState as TransactionStateModel;
class FailedState extends TransactionState implements ITransactionState
{

    public function proceed() : void
    {
        // TODO: Need implementation
    }

    public static function getDatabaseState(): TransactionStateModel
    {
        return TransactionStateModel::where('name', 'Failed')->first();
    }
}
