<?php

namespace App\Services\Transaction\States;

use App\Contracts\ITransactionState;

class ProcessingState extends BaseState implements ITransactionState
{
    protected string $name = 'Processing';
}
