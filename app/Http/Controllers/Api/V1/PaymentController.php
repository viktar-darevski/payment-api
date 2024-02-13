<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Payments\CreatePaymentRequest;
use App\Http\Resources\V1\PaymentResource;
use App\Services\DataModels\PaymentDataModel;

/**
 * @OA\Info(
 *      title="Payment Service API",
 *      version="0.1.0"
 *  )
 *
 * @OA\OpenApi(
 *     security={{"JWT": {}}}
 *   )
 *
 * @OA\SecurityScheme(
 *       securityScheme="JWT",
 *       type="apiKey",
 *       scheme="bearer",
 *       bearerFormat="JWT",
 *       in="header",
 *       name="Authorization",
 *   )
 */
class PaymentController extends Controller
{
    /**
     * @OA\Post(
     *       path="/api/v1/payments/transaction",
     *       summary="Create a new payment",
     *       operationId="createPayment",
     *       tags={"payments"},
     *       @OA\RequestBody(
     *           required=true,
     *           @OA\JsonContent(ref="#/components/schemas/CreatePaymentRequest")
     *       ),
     *       @OA\Response(
     *           response=200,
     *           description="Payment successfully created",
     *           @OA\JsonContent(ref="#/components/schemas/PaymentResource")
     *       ),
     * )
     */
    public function create(CreatePaymentRequest $request)
    {
        $dto = $request->makeDTO();
        $data = new PaymentDataModel($dto->uuid);
        return new PaymentResource($data);
    }
}
