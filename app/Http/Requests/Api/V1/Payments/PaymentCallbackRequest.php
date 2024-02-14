<?php

namespace App\Http\Requests\Api\V1\Payments;

use App\DTO\Payment\PaymentCallbackDTO;
use App\Http\Requests\Api\V1\BaseRequest;
use App\Rules\PaymentProviderRule;
use Illuminate\Contracts\Validation\ValidationRule;


/**
 * @class PaymentCallbackRequest
 *
 * @OA\Schema(
 *     schema="PaymentCallbackRequest",
 *     title="Create Payment Request",
 *     @OA\Property(property="sessionSecret", type="string", format="uuid"),
 *     @OA\Property(property="provider", type="string", enum={"stripe", "pay-pall"}),
 *  )
 * )
 */
class PaymentCallbackRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'sessionSecret' => 'required|string',
            'provider' => ['required', app(PaymentProviderRule::class)]
        ];
    }



    /**
     * Creates a PaymentCallbackDTO object using the session secret and provider from the input
     * Keep in mind that using this method is safe if validation rules passed successfully
     * @return PaymentCallbackDTO
     */
    public function makeDTO() : PaymentCallbackDTO {
        $sessionSecret = $this->input('sessionSecret');
        $provider = $this->input('provider');
        return new PaymentCallbackDTO($sessionSecret, $provider);
    }
}
