<?php

namespace App\Services\DataModels;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;

class PaymentDataModel extends BaseDataModel
{

    public function __construct(
        private readonly string $title,
        private readonly string $customer_email,
        #[DataCollectionOf(PaymentDataItemModel::class)]
        private readonly DataCollection $items
    ) {

    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getCustomerEmail(): string
    {
        return $this->customer_email;
    }

    public function getItems(): DataCollection
    {
        return $this->items;
    }
}
