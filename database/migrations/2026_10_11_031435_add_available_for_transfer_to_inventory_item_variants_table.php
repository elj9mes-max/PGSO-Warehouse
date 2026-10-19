<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('inventory_item_variants', function (Blueprint $table) {
            if (! Schema::hasColumn('inventory_item_variants', 'available_for_transfer')) {
                $table->integer('available_for_transfer')->default(0)->after('quantity');
            }
        });

        DB::table('inventory_item_variants')->update([
            'available_for_transfer' => DB::raw('quantity'),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventory_item_variants', function (Blueprint $table) {
            if (Schema::hasColumn('inventory_item_variants', 'available_for_transfer')) {
                $table->dropColumn('available_for_transfer');
            }
        });
    }
};
