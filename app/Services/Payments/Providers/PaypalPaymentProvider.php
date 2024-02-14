<?php

namespace App\Services\Payments\Providers;

use App\Services\DataModels\PaymentDataModel;
use phpDocumentor\Reflection\Types\Self_;

class PaypalPaymentProvider  extends BasePaymentProvider implements IPaymentProvider
{
    public const string PROVIDER_NAME = 'paypal';

    #[\Override] public function createPaymentSession(PaymentDataModel $dataModel): PaymentSession
    {
        // TODO: Implement createPaymentSession() method.
    }
}
