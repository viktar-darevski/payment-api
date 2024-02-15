<?php

namespace App\Services\Payments\Providers;


namespace App\Services\Payments\Providers;

use App\Services\DataModels\PaymentDataItemModel;
use App\Services\DataModels\PaymentDataModel;
use Brick\Money\Money;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;

class PaypalPaymentProvider extends BasePaymentProvider implements IPaymentProvider
{
    public const string PROVIDER_NAME = 'paypal';
    private PayPalHttpClient $paypal;

    public function __construct(string $sessionSecret, string $sessionID)
    {
        $this->paypal = app(PayPalHttpClient::class);
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
        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        $request->body = [
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'amount' => [
                        'currency_code' => $dataModel->getCurrency(),
                        'value' => $lineItems['total']->getAmount(),
                        'breakdown' => [
                            'item_total' => [
                                'currency_code' => $dataModel->getCurrency(),
                                'value' => $lineItems['total']->getAmount(),
                            ]
                        ]
                    ],
                    'items' => $lineItems['lines'],
                ]
            ],
            'application_context' => [
                'landing_page' => 'BILLING',
                'return_url' => $this->getSuccessUrl(),
                'cancel_url' => $this->getCancelUrl(),
            ]
        ];

        try {
            $response = $this->paypal->execute($request);
            $paypalSession = $response->result;
        } catch (\Exception $e) {
            throw new PaymentException($e->getMessage());
        }

        return new PaymentSession($paypalSession->id, $paypalSession->links[1]->href, $this->name);
    }



    private function createLineItems(PaymentDataModel $dataModel): array
    {
        $total = Money::zero($dataModel->getCurrency());
        $line_items = [];
        /** @var PaymentDataItemModel $item */
        foreach ($dataModel->getItems()->items() as $item) {
            $line_items[] = [
                'name' => $item->getName(),
                'unit_amount' => [
                    'currency_code' => 'USD',
                    'value' => $item->getValue()->getAmount(),
                ],
                'quantity' => $item->getQuantity(),
                'description' => $item->getDescription(),
            ];

            $total = $total->plus($item->getValue());
        }


        // should be used DTO instead of array, but lets save a time
        return [
           'lines' => $line_items,
            'total' => $total,
        ];
    }
}
