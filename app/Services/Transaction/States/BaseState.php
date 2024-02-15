<?php

namespace App\Services\Transaction\States;

use App\Models\Transaction;
use App\Models\TransactionState as TransactionStateModel;
use Illuminate\Support\Facades\Log;

abstract class BaseState
{

    protected string $name;

    public function process(Transaction $transaction): void
    {
        Log::channel('transaction')->info("Transaction {id} is changed state to the {name}", [
            'id' => $transaction->id,
            'name' => $this->getName(),
        ]);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDatabaseState(): TransactionStateModel
    {
        return TransactionStateModel::where('name', $this->getName())->first();
    }

}
