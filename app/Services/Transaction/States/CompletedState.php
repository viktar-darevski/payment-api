<?php

namespace App\Services\Transaction\States;

use App\Contracts\ITransactionState;
use App\Events\PaymentProcessed;
use App\Models\TransactionState as TransactionStateModel;
use App\Services\Payments\Providers\PaymentCallback;

class CompletedState extends TransactionState implements ITransactionState
{

    public function proceed() : void
    {
        Log::channel('transaction')->info("Transaction {id} is Completed", [
            'id' => $this->transaction->id,
        ]);

        // Just example of how we can use events to handle actions related to transaction state changing
        $callback = new PaymentCallback($this->transaction->callback_url, $this->transaction->session_code, 'success' );
        event(new PaymentProcessed($callback, $this->transaction));
    }

    public static function getDatabaseState(): TransactionStateModel
    {
        return TransactionStateModel::where('name', 'Completed')->first();
    }
}
