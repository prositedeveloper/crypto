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
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sell_currency_id')->constrained('currencies')->cascadeOnDelete();
            $table->foreignId('buy_currency_id')->constrained('currencies')->cascadeOnDelete();
            $table->decimal('sell_amount', 18, 8);
            $table->decimal('buy_amount', 18, 8);
            $table->decimal('price', 18, 8);
            $table->enum('status', ['open', 'closed'])->default('open');
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
