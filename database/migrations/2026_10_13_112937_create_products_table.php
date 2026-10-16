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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('product_categories')->restrictOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku', 64)->unique();
            $table->string('short_description')->nullable();
            $table->text('description')->nullable();

            // Informação do produto (núcleo da marca "sem glúten")
            $table->text('ingredients')->nullable();
            $table->text('allergens')->nullable();
            $table->text('cross_contamination_notice')->nullable();
            $table->string('gluten_free_certification')->nullable();
            $table->json('nutrition')->nullable();
            $table->text('storage_info')->nullable();
            $table->text('preparation_info')->nullable();

            // draft | published | inactive  (ver App\Enums\ProductStatus)
            $table->string('status', 20)->default('draft')->index();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_new')->default(false);
            $table->boolean('is_favorite')->default(false);
            $table->boolean('is_purchasable')->default(true);
            // Identificação e preço
            $table->unsignedBigInteger('price')->default(0);        // centavos de MZN (1 MZN = 100)
            $table->unsignedBigInteger('sale_price')->nullable();   // preço promocional, centavos
            $table->unsignedInteger('weight_grams')->nullable();    // peso/conteúdo, útil para a entrega

            // Stock
            $table->boolean('track_stock')->default(true);
            $table->unsignedInteger('quantity_on_hand')->default(0);   // em stock (inclui o reservado)
            $table->unsignedInteger('low_stock_threshold')->default(0); // 0 = sem alerta
            $table->unsignedInteger('quantity_reserved')->default(0);  // comprometido com encomendas abertas
            $table->unsignedInteger('quantity_unavailable')->default(0); // danificado/em quarentena, ainda fisicamente em stock
            $table->timestamps();
            $table->softDeletes(); // "arquivar" = soft delete

            $table->index(['status', 'category_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
