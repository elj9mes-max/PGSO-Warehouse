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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        $defaultCategories = [
            'Hardware & Tools',
            'Office Supplies',
            'Medical Logistics',
            'IT Equipment',
            'Safety Gear',
        ];

        if (Schema::hasTable('inventory_items')) {
            $existing = DB::table('inventory_items')->distinct()->pluck('category')->filter()->all();
            $defaultCategories = array_unique(array_merge($defaultCategories, $existing));
        }

        foreach ($defaultCategories as $categoryName) {
            DB::table('categories')->insertOrIgnore([
                'name' => $categoryName,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
