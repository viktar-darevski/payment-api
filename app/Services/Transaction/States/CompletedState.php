<?php

namespace App\Services\Transaction\States;

use App\Contracts\ITransactionState;
use App\Events\PaymentProcessed;
use App\Models\Transaction;
use App\Services\Payments\Providers\PaymentCallback;

class CompletedState extends BaseState implements ITransactionState
{
   protected string $name = 'Completed';

   public function process(Transaction $transaction): void
   {
       parent::process($transaction);
       // Just showing that we could relay on events
       $callback = new PaymentCallback($transaction->callback_url, $transaction->session_code, 'success' );
       event(new PaymentProcessed($callback, $transaction));
   }
}
