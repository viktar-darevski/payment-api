<?php

namespace App\Models;


use App\Contracts\ITransactionState;
use Brick\Math\RoundingMode;
use Brick\Money\Money;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'title',
        'provider',
        'callback_url',
        'session_code',
        'customer_email',
        'currency',
        'items',
        'user_id',
        'state_id',
        'total',
        'provider_secret_code',
        'provider_session_id',
    ];

    protected static function booted()
    {
        // On saving the transaction set the total as the sum of all the items
        static::saving(function (Transaction $transaction) {
            $transaction->total = $transaction->getTotal()->getMinorAmount()->toBigInteger();
        });
    }


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(TransactionState::class);
    }

    public function setState(TransactionState $state)
    {
        $this->state()->associate($state);
    }


    /**
     * Retrieving the Items from JSON
     * @param $value
     * @return Collection
     */
    public function getItemsAttribute($value): Collection
    {
        $items = json_decode($value, true);

        return collect($items)->map(function ($item) {
            return new TransactionItem(
                $item['name'],
                $item['description'],
                $item['quantity'],
                Money::ofMinor($item['amount'], $item['currency'],)
            );
        });
    }


    /**
     * Saving the Items as a JSON in database
     * @param Collection $value
     * @return void
     */
    public function setItemsAttribute(Collection $value) : void
    {
        $this->attributes['items'] = json_encode($value->map(function (TransactionItem $item) {
            return [
                'name' => $item->getName(),
                'amount' => $item->getAmount()->getMinorAmount()->toInt(),
                'description' => $item->getDescription(),
                'quantity' => $item->getQuantity(),
                'currency' => $item->getAmount()->getCurrency(),
            ];
        })->toArray());
    }

    public function getTotal(): Money
    {
        return $this->items->reduce(function ($carry, TransactionItem $item) {
            return $carry->plus($item->getAmount()->multipliedBy($item->getQuantity()));
        }, Money::zero($this->currency));
    }


    public function setProviderSecretCodeAttribute($value): void
    {
        $this->attributes['provider_secret_code'] = Hash::make($value);
    }


    public function changeState(string $stateClass)
    {
        $databaseState = $stateClass::getDatabaseState();
        $this->state()->associate($databaseState);
        $this->save();
    }


    /**
     * @return void
     */
    public function processState(): void
    {
        $state = $this->state;
        $stateClass = "App\\Services\\Transaction\\States\\" . $state->name . "State";
        /** @var ITransactionState $stateObject */
        $stateObject = app($stateClass, ['transaction' => $this]);
        $stateObject->proceed();
    }

}
