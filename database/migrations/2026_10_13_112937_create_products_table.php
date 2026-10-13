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
