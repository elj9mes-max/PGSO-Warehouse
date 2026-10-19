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
        // Requisition Requests (Headers for RIS / PR forms)
        Schema::create('requisition_requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_number', 64)->unique()->index();
            $table->string('request_type', 20)->default('RIS')->index(); // 'RIS' (Requisition & Issue Slip) or 'PR' (Purchase Request)

            // Requisition Custody & Requester
            $table->foreignId('office_id')->constrained('offices')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            // Workflow Status
            $table->string('status', 30)->default('pending')->index(); // 'pending', 'approved', 'issued', 'rejected', 'cancelled'

            // Requisition Context & COA Form Metadata
            $table->text('purpose')->nullable();
            $table->string('division', 191)->nullable();
            $table->string('responsibility_center_code', 64)->nullable()->default('1061');
            $table->string('ris_no', 64)->nullable()->index();
            $table->string('sai_no', 64)->nullable();
            $table->string('air_number', 64)->nullable();
            $table->string('po_number', 64)->nullable();
            $table->string('supplier', 191)->nullable();

            // Official Signees & Workflow Approvals
            $table->string('requested_by_name', 191)->nullable();
            $table->string('requested_by_designation', 191)->nullable();

            $table->foreignId('approved_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('approved_by_name', 191)->nullable();
            $table->string('approved_by_designation', 191)->nullable();
            $table->timestamp('approved_at')->nullable();

            $table->foreignId('issued_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('issued_by_name', 191)->nullable();
            $table->string('issued_by_designation', 191)->nullable();
            $table->timestamp('issued_at')->nullable();

            $table->foreignId('received_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('received_by_name', 191)->nullable();
            $table->string('received_by_designation', 191)->nullable();
            $table->timestamp('received_at')->nullable();

            // Notes & Cancellation/Rejection Reason
            $table->text('remarks')->nullable();
            $table->text('rejection_reason')->nullable();

            $table->timestamps();

            // Compound indexes
            $table->index(['office_id', 'status']);
            $table->index(['user_id', 'status']);
            $table->index(['request_type', 'created_at']);
        });

        // Requisition Request Items (Line items requested)
        Schema::create('requisition_request_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requisition_request_id')->constrained('requisition_requests')->cascadeOnDelete();
            $table->foreignId('inventory_item_variant_id')->nullable()->constrained('inventory_item_variants')->nullOnDelete();

            // Item Identification & Snapshot
            $table->string('stock_no', 64)->nullable();
            $table->string('item_name', 191);
            $table->string('variant_name', 191)->nullable();
            $table->text('description')->nullable();
            $table->string('unit', 50)->default('pcs');

            // Quantities
            $table->integer('quantity_requested')->default(1);
            $table->integer('quantity_issued')->default(0);

            // Costs & Valuation
            $table->decimal('unit_cost', 12, 2)->default(0.00);
            $table->decimal('total_cost', 12, 2)->default(0.00);

            // Item-level Remarks & Line Status
            $table->text('remarks')->nullable();
            $table->string('status', 30)->default('pending'); // 'pending', 'approved', 'issued', 'rejected', 'out_of_stock'

            $table->timestamps();

            $table->index(['requisition_request_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requisition_request_items');
        Schema::dropIfExists('requisition_requests');
    }
};
