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
        Schema::create('inventory_batches', function (Blueprint $table) {
            $table->id();
            $table->string('batch_number', 64)->unique();
            $table->foreignId('inventory_item_id')->constrained('inventory_items')->cascadeOnDelete();
            $table->foreignId('inventory_item_variant_id')->constrained('inventory_item_variants')->cascadeOnDelete();
            $table->foreignId('office_id')->nullable()->constrained('offices')->nullOnDelete();
            $table->string('po_number', 64)->nullable()->index();
            $table->string('pr_number', 64)->nullable()->index();
            $table->string('supplier', 191)->nullable();
            $table->date('delivery_date')->nullable();
            $table->decimal('unit_cost', 12, 2)->default(0.00);
            $table->integer('quantity_received')->default(0);
            $table->integer('quantity_remaining')->default(0);
            $table->string('status', 30)->default('active');
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->index(['inventory_item_variant_id', 'status']);
            $table->index(['office_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_batches');
    }
};
