<?php

namespace App\Services\Transaction\States;

use App\Contracts\ITransactionState;
use App\Models\TransactionState as TransactionStateModel;
use Illuminate\Support\Facades\Log;

class NewState extends TransactionState implements ITransactionState
{

    public function proceed(): void
    {
        // Example how we could control the flow of the transaction state
        if ($this->transaction->provider_secret_code && $this->transaction->provider_session_id) {
            Log::channel('transaction')->info("Transaction {id} is changed from the New state to the Processing", [
                'id' => $this->transaction->id,
            ]);
            $this->transaction->changeState(ProcessingState::class);
        } else {
            Log::channel('transaction')->info("Transaction {id} is changed from the New state to the Failed", [
                'id' => $this->transaction->id,
            ]);
            $this->transaction->changeState(FailedState::class);
        }
    }

    public static function getDatabaseState(): TransactionStateModel
    {
        return TransactionStateModel::where('name', 'New')->first();
    }
}
