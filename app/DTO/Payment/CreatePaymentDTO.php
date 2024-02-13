<?php

namespace App\DTO\Payment;

use App\DTO\BaseDTO;
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
                                DataCollection $items)
    {
        $this->uuid = $uuid;
        $this->title = $title;
        $this->currency = $currency;
        $this->items = $items;
        $this->provider = $provider;
        $this->callback_url = $callback_url;
        $this->session_code = $session_code;
    }
}
