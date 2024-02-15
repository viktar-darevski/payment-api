<?php

namespace App\Contracts\Services;

use App\DTO\Payment\CreatePaymentDTO;
use App\DTO\Payment\PaymentCallbackDTO;
use App\Models\User;
use App\Services\Payments\Providers\PaymentCallback;
use App\Services\Payments\Providers\PaymentSession;

interface ITransactionService
{
    public function createPayment(CreatePaymentDTO $data, User $user) : PaymentSession;

    public function processedPayment(PaymentCallbackDTO $data) : PaymentCallback;
}
