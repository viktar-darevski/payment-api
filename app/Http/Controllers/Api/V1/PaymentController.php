<?php
namespace App\Http\Controllers\Api\V1;

use App\Contracts\Services\ITransactionService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Payments\CreatePaymentRequest;
use App\Http\Requests\Api\V1\Payments\PaymentCallbackRequest;
use App\Http\Resources\V1\PaymentResource;

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
    public function create(CreatePaymentRequest $request, ITransactionService $service)
    {
        $dto = $request->makeDTO();
        $paymentSession = $service->createPayment(
            $dto, \Auth::user()
        );

        return new PaymentResource($paymentSession);
    }



    /**
     * @OA\Post(
     *       path="/api/v1/payments/callback/success",
     *       summary="Callback method for success payment",
     *       operationId="callbackSuccess",
     *       tags={"payments"},
     *       @OA\RequestBody(
     *           required=true,
     *           @OA\JsonContent(ref="#/components/schemas/PaymentCallbackRequest")
     *       ),
     *       @OA\Response(
     *           response=301,
     *           description="Making redirect to client site",
     *       ),
     * )
     */
    public function success(PaymentCallbackRequest $request, ITransactionService $service)
    {
        $dto = $request->makeDTO();
        $paymentCallback = $service->processedPayment($dto);
        return redirect($paymentCallback->generateLink());
    }


    public function cancel(PaymentCallbackRequest $request, ITransactionService $service)
    {
        // TODO: implement cancel functionality
        dd($request->makeDTO());
    }
}
