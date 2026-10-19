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
        Schema::create('purchase_requests', function (Blueprint $table) {
            $table->id();
            $table->string('pr_number', 64)->unique()->index();
            $table->foreignId('office_id')->constrained('offices')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('status', 30)->default('pending')->index(); // 'pending', 'approved', 'rejected', 'cancelled'

            $table->text('purpose')->nullable();
            $table->string('responsibility_center_code', 64)->nullable()->default('1061');

            $table->string('requested_by_name', 191)->nullable();
            $table->string('requested_by_designation', 191)->nullable();

            $table->foreignId('approved_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('approved_by_name', 191)->nullable();
            $table->string('approved_by_designation', 191)->nullable();
            $table->timestamp('approved_at')->nullable();

            $table->decimal('total_estimated_cost', 12, 2)->default(0.00);
            $table->text('remarks')->nullable();
            $table->text('rejection_reason')->nullable();

            $table->timestamps();

            $table->index(['office_id', 'status']);
            $table->index(['user_id', 'status']);
        });

        Schema::create('purchase_request_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_request_id')->constrained('purchase_requests')->cascadeOnDelete();
            $table->foreignId('inventory_item_variant_id')->nullable()->constrained('inventory_item_variants')->nullOnDelete();

            $table->string('item_code', 64)->nullable();
            $table->string('barcode', 64)->nullable();
            $table->string('item_name', 191);
            $table->string('variant_name', 191)->nullable();
            $table->text('description')->nullable();
            $table->string('unit', 50)->default('pcs');

            $table->integer('quantity_requested')->default(1);
            $table->decimal('estimated_unit_cost', 12, 2)->default(0.00);
            $table->decimal('estimated_total_cost', 12, 2)->default(0.00);
            $table->text('remarks')->nullable();

            $table->timestamps();

            $table->index('purchase_request_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_request_items');
        Schema::dropIfExists('purchase_requests');
    }
};
