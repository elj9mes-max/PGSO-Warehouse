<?php

namespace Database\Seeders;

use App\Models\InventoryItemVariant;
use App\Models\Office;
use App\Models\OfficeInventory;
use Illuminate\Database\Seeder;

class OfficeInventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $offices = Office::take(3)->get();
        $variants = InventoryItemVariant::take(3)->get();

        foreach ($offices as $index => $office) {
            if ($variant = $variants->get($index)) {
                OfficeInventory::firstOrCreate([
                    'office_id' => $office->id,
                    'inventory_item_variants_id' => $variant->id,
                ]);
            }
        }
    }
}
