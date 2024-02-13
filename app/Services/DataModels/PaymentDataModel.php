<?php

namespace App\Services\DataModels;

class PaymentDataModel extends BaseDataModel
{

    public function __construct(protected readonly string $uuid)
    {
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }
}
