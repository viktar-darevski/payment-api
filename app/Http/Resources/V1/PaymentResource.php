<?php

namespace App\Http\Resources\V1;

use App\Services\DataModels\PaymentDataModel;
use App\Services\Payments\Providers\PaymentSession;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

/**
 * @class PaymentResource
 *
 * @OA\Schema(
 *     schema="PaymentResource",
 *     title="Payment Resource",
 *     required={"uuid"}
 * )
 */
class PaymentResource extends JsonResource
{
    /**
     * @var PaymentSession
     */
    public $resource;

    /**
     * @OA\Property(
     *     property="payment",
     *     type="object",
     *     @OA\Property(
     *         property="payment_link",
     *         type="string",
     *         format="url",
     *         description="The payment link to the payment system"
     *     )
     * )
     * @var array
     */
    public static $wrap = 'payment';

    /**
     * @param Request $request
     * @return array|JsonSerializable|Arrayable
     */
    public function toArray(Request $request): array|JsonSerializable|Arrayable
    {
        return [
            'payment_link' => $this->resource->getPaymentUrl(),
        ];
    }
}
