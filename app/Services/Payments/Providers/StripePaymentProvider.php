<?php

namespace App\Services\Payments\Providers;

use App\Services\DataModels\PaymentDataItemModel;
use App\Services\DataModels\PaymentDataModel;
use Stripe\StripeClient;

class StripePaymentProvider extends BasePaymentProvider implements IPaymentProvider
{
    public const string PROVIDER_NAME = 'stripe';
    private StripeClient $stripe;

    public function __construct(string $sessionSecret, string $sessionID)
    {
        $this->stripe = app(StripeClient::class);

        $this->name = self::PROVIDER_NAME;

        parent::__construct($sessionSecret, $sessionID);
    }


    /**
     * @param PaymentDataModel $dataModel
     * @return PaymentSession
     * @throws PaymentException
     */
    public function createPaymentSession(PaymentDataModel $dataModel): PaymentSession
    {
        $lineItems = $this->createLineItems($dataModel);

        try {
            $stripeSession =$this->stripe->checkout->sessions->create(
                [
                    'success_url' =>  $this->getSuccessUrl(),
                    'cancel_url' => $this->getCancelUrl(),
                    'line_items' => $lineItems,
                    'locale' => $this->getLocale(),
                    'payment_method_types' => ['card'],
                    'mode' => 'payment',
                    'customer_email' => $dataModel->getCustomerEmail(),
                ],
            );
        } catch (\Stripe\Exception\ApiErrorException $e) {
            // TODO: expand exception data
            throw new PaymentException($e->getMessage());
        }

        return new PaymentSession($stripeSession->id, $stripeSession->url, $this->name, $this->sessionSecret, $this->sessionID );
    }

    private function createLineItems(PaymentDataModel $dataModel) : array{
        $line_items = [];
        /** @var PaymentDataItemModel $item */
        foreach ($dataModel->getItems()->items() as $item) {
            $line_items[] = [
                'price_data' => [
                    'currency' => $item->getValue()->getCurrency(),
                    'unit_amount' => $item->getValue()->getMinorAmount()->toInt(),
                    'product_data' => [
                        'name' => $item->getName(),
                        'description' => $item->getDescription(),
                    ],
                ],
                'quantity' => $item->getQuantity(),
            ];
        }

        return $line_items;
    }
}
