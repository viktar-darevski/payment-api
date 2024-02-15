<?php

namespace App\Repositories;

use App\Contracts\Repositories\ITransactionRepository;
use App\DTO\Payment\CreatePaymentDTO;
use App\DTO\Payment\PaymentItemDTO;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use App\Services\Transaction\States\NewState;

class TransactionRepository implements ITransactionRepository
{
    public function create(CreatePaymentDTO $data, User $user): Transaction
    {
        $transaction = new Transaction([
            'uuid' => $data->uuid,
            'title' => $data->title,
            'provider' => $data->provider,
            'callback_url' => $data->callback_url,
            'session_code' => $data->session_code,
            'customer_email' => $data->customer_email,
            'currency' => $data->currency,
            'user_id' => $user->id,
            ]
        );
        $transaction->setState(NewState::getDatabaseState());

        $items = collect();
        /**
         * @var PaymentItemDTO $item
         */
        foreach ($data->getItems()->items() as $item) {
             $items->add(new TransactionItem(
                $item->getName(),
                $item->getDescription(),
                $item->getQuantity(),
                $item->getValue(),
            ));
        }

        $transaction->setItemsAttribute($items);
        $transaction->save();

        return $transaction;
    }


    public function findBySessionID(string $sessionId): ?Transaction
    {
        return Transaction::Where('provider_session_id', $sessionId)->firstOrFail();
    }
}
