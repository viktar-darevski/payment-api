<?php

namespace App\DTO\Payment;

use App\DTO\BaseDTO;
use Brick\Money\Money;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
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
     *     property="description",
     *     type="string",
     *     description="The description for payment item"
     * )
     * @var string
     */
    #[StringType]
    public string $description;


    /**
     * @OA\Property(
     *     property="quantity",
     *     type="integer",
     *     description="Quanity of the payment item",
     * )
     * @var int
     */
    #[IntegerType]
    public int $quantity;


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

    public function __construct(string $name, Money $value, string $description, int $quantity)
    {
        $this->name = $name;
        $this->value = $value;
        $this->description = $description;
        $this->quantity = $quantity;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getValue(): Money
    {
        return $this->value;
    }

}
