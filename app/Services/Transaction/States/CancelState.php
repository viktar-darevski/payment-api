<?php

namespace App\Services\Transaction\States;

use App\Contracts\ITransactionState;

class CancelState extends BaseState implements ITransactionState
{

   protected string $name = 'Cancel';
}
