<?php

namespace Database\Factories;

use App\Models\TransactionState;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionStateFactory extends Factory
{
    protected $model = TransactionState::class;

    public function definition()
    {
        return [
            'name' => $this->faker->randomElement(['New', 'Processing', 'Completed', 'Failed', 'Cancel']),
        ];
    }
}
