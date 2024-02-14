<?php

namespace App\Services\Payments;

use App\Contracts\Services\IPaymentsService;
use App\Services\DataModels\PaymentDataModel;
use App\Services\Payments\Providers\PaymentFactory;
use App\Services\Payments\Providers\PaymentSession;

use Illuminate\Support\Str;

class PaymentsService implements IPaymentsService
{
    public function createPayment(PaymentDataModel $dataModel, string $provider) : PaymentSession
    {
        $paymentSecret = Str::random(32);
        $paymentProvider = PaymentFactory::create($provider, $paymentSecret);
        $session = $paymentProvider->createPaymentSession($dataModel);
        $session->setPaymentSecret($paymentSecret);
        return $session;
    }
}
