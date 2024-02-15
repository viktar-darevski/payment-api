<?php

namespace App\Services\Transaction\States;

use App\Contracts\ITransactionState;
use App\Models\TransactionState as TransactionStateModel;
use Illuminate\Support\Facades\Log;

class ProcessingState extends TransactionState implements ITransactionState
{

    public function proceed() : void
    {
        Log::channel('transaction')->info("Transaction {id} is changing from the Processing state to the Completed", [
            'id' => $this->transaction->id,
        ]);
        $this->transaction->changeState(CompletedState::class);
    }

    public static function getDatabaseState(): TransactionStateModel
    {
        return TransactionStateModel::where('name', 'Processing')->first();
    }
}
