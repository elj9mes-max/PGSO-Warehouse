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
        Schema::create('ssmi_reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_no', 100)->unique()->index();
            $table->string('period_text', 191)->nullable();
            $table->date('period_date')->nullable()->index();

            // Scope & Associations
            $table->foreignId('office_id')->nullable()->constrained('offices')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            // Snapshot of RIS reference numbers (up to 8 slots matching the paper matrix)
            $table->json('ris_columns')->nullable();

            // Report Totals
            $table->decimal('total_amount', 14, 2)->default(0);

            // Lifecycle status: draft, finalized, submitted, archived
            $table->string('status', 50)->default('draft')->index();

            // Signatories Snapshot
            $table->string('prepared_by_name', 191)->nullable()->default('Raymond G. Amat');
            $table->string('prepared_by_designation', 191)->nullable()->default('AA-III Warehouse');
            $table->string('approved_by_name', 191)->nullable()->default('Joel Friginal');
            $table->string('approved_by_designation', 191)->nullable()->default('SAO');
            $table->string('accounting_officer_name', 191)->nullable();
            $table->timestamp('accounting_received_at')->nullable();

            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->index(['created_at', 'status']);
        });

        Schema::create('ssmi_report_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ssmi_report_id')->constrained('ssmi_reports')->cascadeOnDelete();
            $table->unsignedInteger('row_order')->default(1)->index();

            // Optional Catalog Variant link
            $table->foreignId('inventory_item_variant_id')->nullable()->constrained('inventory_item_variants')->nullOnDelete();

            $table->string('stock_no', 100)->nullable();
            $table->text('description')->nullable();
            $table->string('unit', 50)->nullable();

            // Quantities across the 8 RIS columns
            $table->json('ris_quantities')->nullable();

            // Row Totals
            $table->decimal('total_quantity', 12, 2)->default(0);
            $table->decimal('unit_cost', 12, 2)->default(0);
            $table->decimal('total_cost', 14, 2)->default(0);

            $table->timestamps();

            $table->index(['ssmi_report_id', 'row_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ssmi_report_items');
        Schema::dropIfExists('ssmi_reports');
    }
};
