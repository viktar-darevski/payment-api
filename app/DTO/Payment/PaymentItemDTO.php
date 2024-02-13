<?php

namespace App\DTO\Payment;

use App\DTO\BaseDTO;
use Brick\Money\Money;
use Spatie\LaravelData\Attributes\Validation\StringType;

/**
 * @class PaymentItemDTO
 *
 * @OA\Schema(
 *     schema="PaymentItemDTO",
 *     title="Payment Item",
 *     required={"name", "value"}
 * )
 */
class PaymentItemDTO extends BaseDTO
{
    /**
     * @OA\Property(
     *     property="name",
     *     type="string",
     *     description="The name of the payment item"
     * )
     * @var string
     */
    #[StringType]
    public string $name;

    /**
     * @OA\Property(
     *     property="value",
     *     type="string",
     *     description="The value of the payment item",
     *     @OA\Schema(ref="#/components/schemas/Money")
     * )
     * @var Money
     */
    public Money $value;

    public function __construct(string $name, Money $value)
    {
        $this->name = $name;
        $this->value = $value;
    }
}
