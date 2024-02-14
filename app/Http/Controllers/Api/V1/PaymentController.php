<?php
namespace App\Http\Controllers\Api\V1;

use App\Contracts\Services\IPaymentsService;
use App\DTO\Payment\PaymentItemDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Payments\CreatePaymentRequest;
use App\Http\Requests\Api\V1\Payments\PaymentCallbackRequest;
use App\Http\Resources\V1\PaymentResource;
use App\Services\DataModels\PaymentDataItemModel;
use App\Services\DataModels\PaymentDataModel;
use Spatie\LaravelData\DataCollection;

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
    public function create(CreatePaymentRequest $request, IPaymentsService $service)
    {
        $dto = $request->makeDTO();

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
        }, $dto->getItems()->items());


        $itemCollection = new DataCollection(PaymentDataItemModel::class, $paymentItems);

        $paymentSession = $service->createPayment(
            new PaymentDataModel(
                $dto->getTitle(),
                $dto->getCustomerEmail(),
                $itemCollection,
            ), $dto->getProvider(),
        );

        dd($paymentSession);

        return new PaymentResource($data);
    }


    public function success(PaymentCallbackRequest $request, IPaymentsService $service)
    {
        dd($request->makeDTO());
    }


    public function cancel(PaymentCallbackRequest $request, IPaymentsService $service)
    {
        dd($request->makeDTO());
    }
}
