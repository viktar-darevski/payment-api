<?php

namespace App\Services\Payments\Providers;

use App\Services\DataModels\PaymentDataModel;

interface IPaymentProvider
{
    public function name(): string;

    public function createPaymentSession(PaymentDataModel $dataModel): PaymentSession;

    public function __construct($sessionSecret);
}
