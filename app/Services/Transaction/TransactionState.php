<?php

namespace App\Services\Transaction;

use App\Contracts\ITransactionState;
use App\Models\Transaction;
use App\Services\Transaction\Checkers\AllowedChecker;
use App\Services\Transaction\Checkers\CheckIsSuccessCreatedPayment;
use App\Services\Transaction\Checkers\ICheckerInterface;
use App\Services\Transaction\States\CompletedState;
use App\Services\Transaction\States\FailedState;
use App\Services\Transaction\States\NewState;
use App\Services\Transaction\States\ProcessingState;
use Illuminate\Support\Facades\Log;

class TransactionState
{
    protected static array $allowedTransitions = array(
        NewState::class => array(
            ProcessingState::class => CheckIsSuccessCreatedPayment::class,
            FailedState::class => AllowedChecker::class,
        ),
        ProcessingState::class => array(
            CompletedState::class => AllowedChecker::class,
            FailedState::class => AllowedChecker::class,
        )
    );

    /**
     * @throws TransitStateException
     */
    public static function transit(Transaction $transaction, ITransactionState $newState): void
    {
        Log::channel('transaction')->info("Transaction {id} is trying to transit state from {from} to {to}", [
            'id' => $transaction->id,
            'from' => $transaction->getCurrentState()->getName(),
            'to' => $newState->getName(),
        ]);

        self::canTransitionOrFail($transaction, $transaction->getCurrentState(), $newState);
        $transaction->changeState($newState);
    }

    /**
     * @param Transaction $transaction
     * @param ITransactionState $currentState
     * @param ITransactionState $newState
     * @throws TransitStateException
     */
    protected static function canTransitionOrFail(Transaction $transaction, ITransactionState $currentState, ITransactionState $newState) : void {
        $newStateClass = get_class($newState);
        $currentStateClass = get_class($currentState);
        if (isset(self::$allowedTransitions[$currentStateClass][$newStateClass])) {
            /** @var ICheckerInterface $checkerFunction */
            $checkerFunction = new self::$allowedTransitions[$currentStateClass][$newStateClass];

            if ($checkerFunction->check($transaction)){
                return;
            }
            throw new TransitStateException(
                sprintf("The transaction checker %s is failed",
                    get_class($checkerFunction)
                ),
            );
        }

        throw new TransitStateException(
            sprintf("The state transition from %s to %s is not allowed",
                $currentState->getName(),
                $newState->getName()
            )
        );
    }
}

