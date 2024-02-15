<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         \App\Models\TransactionState::factory()->create([
             'name' => 'New',
         ]);

        \App\Models\TransactionState::factory()->create([
            'name' => 'Processing',
        ]);

        \App\Models\TransactionState::factory()->create([
            'name' => 'Completed',
        ]);

        \App\Models\TransactionState::factory()->create([
            'name' => 'Failed',
        ]);

        \App\Models\TransactionState::factory()->create([
            'name' => 'Cancel',
        ]);

    }
}
