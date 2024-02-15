<?php

namespace App\DTO\Payment;

use App\DTO\BaseDTO;
use Brick\Money\Money;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\StringType;

/**
 * @class PaymentCallbackDTO
 *
 * @OA\Schema(
 *     schema="PaymentCallbackDTO",
 *     title="Payment Callback",
 *     required={"sessionSecret", "provider"}
 * )
 */
class PaymentCallbackDTO extends BaseDTO
{
    /**
     * @OA\Property(
     *     property="sessionSecret",
     *     type="string",
     *     description="The session secret of the payment",
     * )
     * @var string
     */
    #[StringType]
    public string $sessionSecret;

    /**
     * @OA\Property(
     *     property="sessionID",
     *     type="string",
     *     description="The session id of the payment",
     * )
     * @var string
     */
    #[StringType]
    public string $sessionID;

    /**
     * @OA\Property(
     *     property="provider",
     *     type="string",
     *     description="The provider of the payment",
     * )
     * @var string
     */
    #[StringType]
    public string $provider;


    public function __construct(string $sessionSecret, string $provider, string $sessionID)
    {
        $this->sessionSecret = $sessionSecret;
        $this->provider = $provider;
        $this->sessionID = $sessionID;
    }

    public function getSessionSecret(): string
    {
        return $this->sessionSecret;
    }

    public function getProvider(): string
    {
        return $this->provider;
    }

    public function getSessionID(): string
    {
        return $this->sessionID;
    }



}
