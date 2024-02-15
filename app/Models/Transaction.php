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

/**
 * @property-read int id
 * @property string provider_secret_code
 * @property string provider_session_id
 * @property string callback_url
 * @property string session_code
 *
 */
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


    /**
     * Directly update database transaction state in ORM
     * @param TransactionState $state
     * @return void
     */
    public function setState(TransactionState $state)
    {
        $this->state()->associate($state);
    }


    public function changeState(ITransactionState $state)
    {
        // set the state in the database
        $this->setState($state->getDatabaseState());
        $this->save();

        $state->process($this);
    }


    /**
     * @return void
     */
    public function getCurrentState(): ITransactionState
    {
        $state = $this->state;
        $stateClass = "App\\Services\\Transaction\\States\\" . $state->name . "State";
        /** @var ITransactionState $stateObject */
        return app($stateClass, ['transaction' => $this]);
    }

}
