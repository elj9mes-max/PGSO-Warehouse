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
        Schema::create('inventory_item_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_item_id')->constrained('inventory_items')->cascadeOnDelete();
            $table->string('variant_name');
            $table->string('barcode')->unique();
            $table->string('sku')->nullable()->unique();
            $table->text('variant_description')->nullable();
            $table->text('unit')->nullable();
            $table->integer('quantity')->default(0);
            $table->integer('available_for_transfer')->default(0);
            $table->integer('reorder_level')->default(5);
            $table->decimal('unit_cost', 12, 2)->default(0.00);
            $table->string('location')->nullable();
            $table->string('status')->default('In Stock');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_item_variants');
    }
};
