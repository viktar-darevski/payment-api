<?php

namespace App\Contracts\Services;

use App\Services\DataModels\PaymentDataModel;
use App\Services\Payments\Providers\PaymentSession;

interface IPaymentsService
{

    public function createPayment(PaymentDataModel $dataModel, string $provider) : PaymentSession;
}
