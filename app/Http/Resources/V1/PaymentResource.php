<?php

namespace App\Http\Resources\V1;

use App\Services\DataModels\PaymentDataModel;
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
     * @var PaymentDataModel
     */
    public $resource;

    /**
     * @OA\Property(
     *     property="payment",
     *     type="object",
     *     @OA\Property(
     *         property="uuid",
     *         type="string",
     *         format="uuid",
     *         description="The UUID of the payment"
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
            'uuid' => $this->resource->getUuid(),
        ];
    }
}
