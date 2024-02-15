<?php

namespace App\DTO\Payment;

use App\DTO\BaseDTO;
use App\Services\DataModels\PaymentDataItemModel;
use App\Services\DataModels\PaymentDataModel;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;

/**
 * Class CreatePaymentDTO
 *
 * @OA\Schema(
 *     schema="CreatePayment",
 *     title="Create Payment",
 *     required={"uuid", "title", "currency", "items"}
 * )
 */
class CreatePaymentDTO extends BaseDTO
{
    /**
     * @OA\Property(
     *     property="uuid",
     *     type="string",
     *     format="uuid",
     *     description="The UUID of the payment"
     * )
     * @var string
     */
    public string $uuid;

    /**
     * @OA\Property(
     *     property="title",
     *     type="string",
     *     description="The title of the payment"
     * )
     * @var string
     */
    public string $title;

    /**
     * @OA\Property(
     *     property="currency",
     *     type="string",
     *     description="The currency of the payment"
     * )
     * @var string
     */
    public string $currency;

    /**
     * @OA\Property(
     *     property="provider",
     *     type="string",
     *     description="The processing provider of the payment"
     * )
     * @var string
     */
    public string $provider = 'stripe';

    /**
     * @OA\Property(
     *     property="callback_url",
     *     type="url",
     *     description="The callback url"
     * )
     * @var string
     */
    public string $callback_url;

    /**
     * @OA\Property(
     *     property="session_code",
     *     type="string",
     *     description="The session code for the callback payment"
     * )
     * @var string
     */
    public string $session_code;

    /**
     * @OA\Property(
     *     property="customer_email",
     *     type="email",
     *     description="The email of the customer"
     * )
     * @var string
     */
    public string $customer_email;

    /**
     * @OA\Property(
     *     property="items",
     *     type="array",
     *     @OA\Items(ref="#/components/schemas/PaymentItemDTO"),
     *     description="The items of the payment"
     * )
     * @var DataCollection
     */
    #[DataCollectionOf(PaymentItemDTO::class)]
    public DataCollection $items;

    public function __construct(string         $uuid,
                                string         $title,
                                string         $currency,
                                string         $callback_url,
                                string         $session_code,
                                string         $provider,
                                string         $customer_email,
                                DataCollection $items)
    {
        $this->uuid = $uuid;
        $this->title = $title;
        $this->currency = $currency;
        $this->items = $items;
        $this->provider = $provider;
        $this->callback_url = $callback_url;
        $this->session_code = $session_code;
        $this->customer_email = $customer_email;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getProvider(): string
    {
        return $this->provider;
    }

    public function getCallbackUrl(): string
    {
        return $this->callback_url;
    }

    public function getSessionCode(): string
    {
        return $this->session_code;
    }

    public function getCustomerEmail(): string
    {
        return $this->customer_email;
    }

    public function getItems(): DataCollection
    {
        return $this->items;
    }

    public function makeDataModel(): PaymentDataModel
    {
        $paymentItems = array_map(function($item) {
            /**
             * @var $item PaymentItemDTO
             */
            return new PaymentDataItemModel(
                $item->getName(),
                $item->getDescription(),
                $item->getQuantity(),
                $item->getValue()
            );
        }, $this->getItems()->items());
        $itemCollection = new DataCollection(PaymentDataItemModel::class, $paymentItems);

        return new PaymentDataModel(
            $this->getTitle(),
            $this->getCustomerEmail(),
            $itemCollection,
        );
    }

}
