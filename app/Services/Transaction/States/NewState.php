<?php

namespace App\Services\Transaction\States;

use App\Contracts\ITransactionState;

class NewState extends BaseState implements ITransactionState
{
    protected string $name = 'New';
}
