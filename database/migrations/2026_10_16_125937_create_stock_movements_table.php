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
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
               // restrictOnDelete: o produto usa soft delete, o histórico nunca se perde
            $table->foreignId('product_id')->constrained()->restrictOnDelete();
            $table->string('type', 30); // App\Enums\StockMovementType

            $table->integer('on_hand_delta')->default(0);
            $table->integer('reserved_delta')->default(0);
            $table->integer('unavailable_delta')->default(0);

            $table->unsignedInteger('on_hand_after');
            $table->unsignedInteger('reserved_after');
            $table->unsignedInteger('unavailable_after');

            $table->text('reason')->nullable(); // obrigatório em ajuste, perda e dano (validar na app)
            $table->nullableMorphs('reference'); // ex.: Order
            $table->string('idempotency_key', 80)->nullable()->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
            $table->index(['product_id', 'id']);
            $table->index(['type', 'created_at']);
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
