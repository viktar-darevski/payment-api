<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->string('title');
            $table->string('callback_url');
            $table->string('session_code');
            $table->string('customer_email');
            $table->string('currency');
            $table->json('items');
            $table->bigInteger('total');
            $table->enum('provider', ['stripe', 'pay-pall']);
            $table->string('provider_secret_code')->nullable();
            $table->string('provider_session_id')->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('state_id')->constrained('transaction_states');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
