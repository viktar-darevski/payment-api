<?php

namespace App\Contracts\Repositories;

use App\DTO\Payment\CreatePaymentDTO;
use App\Models\Transaction;
use App\Models\User;

/**
 * Interface ITransactionRepository.
 *
 * @package namespace App\Contracts\Repositories\ITransactionRepository;
 */
interface ITransactionRepository
{

    public function create(CreatePaymentDTO $data, User $user): Transaction;

    public function findBySessionID(string $sessionId): ?Transaction;
}
