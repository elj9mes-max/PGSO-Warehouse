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
        Schema::disableForeignKeyConstraints();

        if (Schema::hasTable('inventory_item_variants') && Schema::hasColumn('inventory_item_variants', 'inventory_item_id')) {
            try {
                Schema::table('inventory_item_variants', function (Blueprint $table) {
                    $table->dropForeign(['inventory_item_id']);
                });
            } catch (Throwable $e) {
                // Ignore if not present
            }
        }

        if (Schema::hasTable('inventory_logs') && Schema::hasColumn('inventory_logs', 'inventory_item_id')) {
            try {
                Schema::table('inventory_logs', function (Blueprint $table) {
                    $table->dropForeign(['inventory_item_id']);
                });
            } catch (Throwable $e) {
                // Ignore if not present
            }
        }

        if (Schema::hasTable('stock_cards') && Schema::hasColumn('stock_cards', 'inventory_item_id')) {
            try {
                Schema::table('stock_cards', function (Blueprint $table) {
                    $table->dropForeign(['inventory_item_id']);
                });
            } catch (Throwable $e) {
                // Ignore if not present
            }
        }

        if (Schema::hasTable('inventory_batches') && Schema::hasColumn('inventory_batches', 'inventory_item_id')) {
            try {
                Schema::table('inventory_batches', function (Blueprint $table) {
                    $table->dropForeign(['inventory_item_id']);
                });
            } catch (Throwable $e) {
                // Ignore if not present
            }
        }

        Schema::dropIfExists('inventory_items');

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->string('item_code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('category')->index();
            $table->text('unit');
            $table->integer('quantity')->default(0);
            $table->integer('reorder_level')->default(10);
            $table->decimal('unit_cost', 12, 2)->default(0.00);
            $table->string('location')->nullable();
            $table->string('status')->default('In Stock');
            $table->timestamps();
        });
    }
};
