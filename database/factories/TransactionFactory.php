<?php

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\User;
use App\Services\Transaction\States\NewState;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition()
    {
        return [
            'uuid' => Str::uuid(),
            'title' => $this->faker->sentence,
            'provider' => $this->faker->randomElement(['stripe', 'pay-pall']),
            'callback_url' => $this->faker->url,
            'session_code' => Str::random(10),
            'customer_email' => $this->faker->unique()->safeEmail,
            'currency' => $this->faker->randomElement(['USD', 'EUR']),
            'items' => json_encode($this->getItems()),
            'user_id' => User::factory(),
            'state_id' => NewState::getDatabaseState(),
        ];
    }

    private function getItems()
    {
        $items = [];
        for ($i = 0; $i < $this->faker->numberBetween(1, 5); $i++) {
            $items[] = [
                'name' => $this->faker->word,
                'description' => $this->faker->sentence,
                'quantity' => $this->faker->numberBetween(1, 10),
                'amount' => $this->faker->numberBetween(100, 10000), // in cents
            ];
        }
        return $items;
    }
}
