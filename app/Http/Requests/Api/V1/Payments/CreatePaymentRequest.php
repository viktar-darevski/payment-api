<?php

namespace App\Http\Requests\Api\V1\Payments;

use App\DTO\Payment\CreatePaymentDTO;
use App\DTO\Payment\PaymentItemDTO;
use App\Http\Requests\Api\V1\BaseRequest;
use App\Rules\CurrencyField;
use App\Rules\ItemCurrencyField;
use App\Rules\PaymentProviderRule;
use Brick\Money\Money;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\ValidationException;
use Spatie\LaravelData\DataCollection;


/**
 * @class CreatePaymentRequest
 *
 * @OA\Schema(
 *     schema="CreatePaymentRequest",
 *     title="Create Payment Request",
 *     @OA\Property(property="uuid", type="string", format="uuid"),
 *     @OA\Property(property="title", type="string", format="currency"),
 *     @OA\Property(property="currency", type="string"),
 *     @OA\Property(property="provider", type="string", enum={"stripe", "pay-pall"}),
 *     @OA\Property(property="callback_url", type="string", format="url"),
 *     @OA\Property(property="session_code", type="string", format="string"),
 *     @OA\Property(property="customer_email", type="email", format="string"),
 *     @OA\Property(property="items", type="array", @OA\Items(ref="#/components/schemas/PaymentItemDTO")),
 *  )
 * )
 */
class CreatePaymentRequest extends BaseRequest
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
        $currency = $this->input('currency');
        return [
            'uuid' => 'required|uuid',
            'title' => 'required|string',
            // TODO: move to custom validation rules that check client scopes and existing providers
            'provider' => ['required', app(PaymentProviderRule::class)],
            'callback_url' => 'required|url',
            'session_code' => 'required|string',
            'customer_email' => 'required|email',
            // We need to validate the currency field for ISO 4217 format
            'currency' => ['required', app(CurrencyField::class)],
            'items' => 'required|array',
            'items.*' => 'array',
            'items.*.name' => 'required|string',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|integer',
            // We need to validate each item value for that it has correct value represented in the provided currency
            'items.*.value' => ['required', new ItemCurrencyField($currency)],
        ];
    }

    /**
     * Getting CreatePaymentDTO object with already prepared Money data
     * Keep in mind that using this method is safe if validation rules passed successfully
     * @return CreatePaymentDTO
     */
    public function makeDTO() : CreatePaymentDTO {
        $uuid = $this->input('uuid');
        $title = $this->input('title');
        $currency = $this->input('currency');
        $provider = $this->input('provider');
        $sessionCode = $this->input('session_code');
        $callbackUrl = $this->input('callback_url');
        $customerEmail = $this->input('customer_email');
        $itemDTOs = $this->createPaymentItems($this->input('items'), $currency);

        $itemCollection = new DataCollection(PaymentItemDTO::class, $itemDTOs);

        return new CreatePaymentDTO($uuid, $title, $currency, $callbackUrl, $sessionCode, $provider, $customerEmail, $itemCollection);
    }

    /**
     * Create an array of PaymentItemDTO from given items data
     *
     * @param array $itemsData
     * @param string $currency
     * @return array
     */
    protected function createPaymentItems(array $itemsData, string $currency): array {
        try {
            return array_map(function($item) use ($currency) {
                return new PaymentItemDTO($item['name'], Money::of($item['value'], $currency), $item['description'], $item['quantity']);
            }, $itemsData);
        } catch (\Exception $e) {
            throw ValidationException::withMessages([
                'value' => ['Item value validation error' . $e->getMessage()],
            ]);
        }
    }

}
