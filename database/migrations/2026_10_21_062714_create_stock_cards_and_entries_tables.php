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
        Schema::create('stock_cards', function (Blueprint $table) {
            $table->id();

            // Associations
            $table->foreignId('inventory_item_variant_id')->nullable()->constrained('inventory_item_variants')->nullOnDelete();
            $table->foreignId('inventory_item_id')->nullable()->constrained('inventory_items')->nullOnDelete();
            $table->foreignId('office_id')->nullable()->constrained('offices')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            // Header fields (snapshots matching the physical card)
            $table->string('office', 191)->default('ALL OFFICES');
            $table->string('item_name', 191);
            $table->string('unit', 50)->nullable();
            $table->string('stock_no', 100)->nullable()->index();
            $table->string('card_no', 50)->nullable(); // e.g. "Sheet 1", "Card #1"

            $table->timestamps();

            // Helpful index
            $table->index(['inventory_item_variant_id', 'created_at']);
        });

        Schema::create('stock_card_entries', function (Blueprint $table) {
            $table->id();

            // Parent stock card
            $table->foreignId('stock_card_id')->constrained('stock_cards')->cascadeOnDelete();

            // Line transaction data matching the card ledger columns
            $table->date('entry_date')->nullable()->index();
            $table->string('reference', 191)->nullable(); // P.O. No., IAR No., Beg. Balance, etc.
            $table->decimal('receipt_qty', 12, 2)->nullable(); // Inbound quantity
            $table->string('issue_to', 191)->nullable(); // RIS No., Office / Requesting Party
            $table->decimal('issuance_qty', 12, 2)->nullable(); // Outbound quantity
            $table->decimal('balance_qty', 12, 2)->default(0); // Cumulative running balance
            $table->text('remarks')->nullable(); // Notes, inspector, condition, serials
            $table->unsignedInteger('row_order')->default(0)->index(); // Ordering sequence

            // Optional traceability links
            $table->foreignId('requisition_request_id')->nullable()->constrained('requisition_requests')->nullOnDelete();
            $table->foreignId('purchase_request_id')->nullable()->constrained('purchase_requests')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();

            // Indexes for querying
            $table->index(['stock_card_id', 'row_order']);
            $table->index(['stock_card_id', 'entry_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_card_entries');
        Schema::dropIfExists('stock_cards');
    }
};
