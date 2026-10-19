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
        Schema::create('inventory_logs', function (Blueprint $table) {
            $table->id();
            $table->string('action_type')->index();
            $table->string('batch_id')->nullable()->index();
            $table->string('reference_number')->nullable()->index();

            // Actors & Related Models
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('office_id')->nullable()->constrained('offices')->nullOnDelete();
            $table->foreignId('inventory_item_id')->nullable()->constrained('inventory_items')->nullOnDelete();
            $table->foreignId('inventory_item_variant_id')->nullable()->constrained('inventory_item_variants')->nullOnDelete();

            // Historical Name Snapshots (preserved even if parent record is deleted or renamed)
            $table->string('office_name')->nullable();
            $table->string('item_name')->nullable();
            $table->string('variant_name')->nullable();

            // Quantity & Stock Delta
            $table->integer('quantity')->nullable();
            $table->integer('quantity_before')->nullable();
            $table->integer('quantity_after')->nullable();

            // Transfer / Release metadata
            $table->string('recipient')->nullable();
            $table->string('purpose')->nullable();

            // Attribute Changes (Item / Variant edits or adjustments)
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();

            // Notes, Audit & Network Metadata
            $table->text('remarks')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();

            $table->timestamps();

            // Indexes for common query filters
            $table->index(['action_type', 'created_at']);
            $table->index(['office_id', 'created_at']);
            $table->index(['inventory_item_id', 'created_at']);
            $table->index(['inventory_item_variant_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_logs');
    }
};
