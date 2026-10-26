<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\InventoryItemVariant;
use Illuminate\Database\Seeder;

class InventoryItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            [
                'item_code' => 'ITM-8821',
                'variant_name' => 'Standard Industrial Pallets',
                'sku' => 'ITM-8821',
                'barcode' => 'BAR-8821',
                'variant_description' => 'Heavy duty treated pine (1200 x 1000mm)',
                'category' => 'Hardware & Tools',
                'unit' => 'units',
                'quantity' => 42,
                'available_for_transfer' => 42,
                'reorder_level' => 15,
                'unit_cost' => 1450.00,
                'location' => 'Aisle 3 • Bay B',
                'status' => 'In Stock',
                'is_for_inspection' => false,
            ],
            [
                'item_code' => 'ITM-8819',
                'variant_name' => 'Archival Seal Tape Dispenser Rolls',
                'sku' => 'ITM-8819',
                'barcode' => 'BAR-8819',
                'variant_description' => 'Heavy duty clear sealing tape 48mm x 100m',
                'category' => 'Office Supplies',
                'unit' => 'rolls',
                'quantity' => 120,
                'available_for_transfer' => 120,
                'reorder_level' => 30,
                'unit_cost' => 85.00,
                'location' => 'Aisle 1 • Bay A',
                'status' => 'In Stock',
                'is_for_inspection' => false,
            ],
            [
                'item_code' => 'ITM-8804',
                'variant_name' => 'Wireless Barcode Scanners',
                'sku' => 'ITM-8804',
                'barcode' => 'BAR-8804',
                'variant_description' => '2D handheld scanner with USB cradle dock',
                'category' => 'IT Equipment',
                'unit' => 'units',
                'quantity' => 4,
                'available_for_transfer' => 4,
                'reorder_level' => 8,
                'unit_cost' => 4800.00,
                'location' => 'Tech Cabinet',
                'status' => 'Low Stock',
                'is_for_inspection' => false,
            ],
            [
                'item_code' => 'ITM-8799',
                'variant_name' => 'Disaster Response First Aid Kits',
                'sku' => 'ITM-8799',
                'barcode' => 'BAR-8799',
                'variant_description' => 'Compliant with LGU Emergency Services Spec',
                'category' => 'Medical Logistics',
                'unit' => 'kits',
                'quantity' => 75,
                'available_for_transfer' => 75,
                'reorder_level' => 25,
                'unit_cost' => 2200.00,
                'location' => 'Aisle 4 • Bay C',
                'status' => 'In Stock',
                'is_for_inspection' => false,
            ],
            [
                'item_code' => 'ITM-8750',
                'variant_name' => 'High-Visibility Reflective Vests',
                'sku' => 'ITM-8750',
                'barcode' => 'BAR-8750',
                'variant_description' => 'ANSI Class 2 reflective strips (Neon Yellow)',
                'category' => 'Safety Gear',
                'unit' => 'pieces',
                'quantity' => 210,
                'available_for_transfer' => 210,
                'reorder_level' => 50,
                'unit_cost' => 320.00,
                'location' => 'Aisle 2 • Bay B',
                'status' => 'In Stock',
                'is_for_inspection' => false,
            ],
            [
                'item_code' => 'ITM-8712',
                'variant_name' => 'Hydraulic Hand Pallet Trucks',
                'sku' => 'ITM-8712',
                'barcode' => 'BAR-8712',
                'variant_description' => 'Capacity: 3.0 Tons • Dual Polyurethane Wheels',
                'category' => 'Hardware & Tools',
                'unit' => 'units',
                'quantity' => 0,
                'available_for_transfer' => 0,
                'reorder_level' => 4,
                'unit_cost' => 19500.00,
                'location' => 'Aisle 1 • Bay A',
                'status' => 'Out of Stock',
                'is_for_inspection' => false,
            ],
            [
                'item_code' => 'ITM-PEN-01-BLU',
                'variant_name' => 'MyGel Signpen 0.5mm - Blue',
                'sku' => 'PEN-01-BLU',
                'barcode' => '480088910011',
                'variant_description' => 'Standard Royal Blue Gel Ink',
                'category' => 'Office Supplies',
                'unit' => 'pieces',
                'quantity' => 100,
                'available_for_transfer' => 100,
                'reorder_level' => 15,
                'unit_cost' => 38.50,
                'location' => 'Aisle 1 • Shelf C-2',
                'status' => 'In Stock',
                'is_for_inspection' => false,
            ],
            [
                'item_code' => 'ITM-PEN-01-RED',
                'variant_name' => 'MyGel Signpen 0.5mm - Red',
                'sku' => 'PEN-01-RED',
                'barcode' => '480088910012',
                'variant_description' => 'Correction & Auditing Red Gel Ink',
                'category' => 'Office Supplies',
                'unit' => 'pieces',
                'quantity' => 40,
                'available_for_transfer' => 40,
                'reorder_level' => 15,
                'unit_cost' => 38.50,
                'location' => 'Aisle 1 • Shelf C-2',
                'status' => 'In Stock',
                'is_for_inspection' => false,
            ],
            [
                'item_code' => 'ITM-PEN-01-BLK',
                'variant_name' => 'MyGel Signpen 0.5mm - Black',
                'sku' => 'PEN-01-BLK',
                'barcode' => '480088910013',
                'variant_description' => 'Standard Document Black Gel Ink',
                'category' => 'Office Supplies',
                'unit' => 'pieces',
                'quantity' => 80,
                'available_for_transfer' => 80,
                'reorder_level' => 15,
                'unit_cost' => 38.50,
                'location' => 'Aisle 1 • Shelf C-2',
                'status' => 'In Stock',
                'is_for_inspection' => false,
            ],
        ];

        foreach ($items as $item) {
            Category::firstOrCreate(['name' => $item['category']]);
            InventoryItemVariant::updateOrCreate(
                ['barcode' => $item['barcode']],
                $item
            );
        }
    }
}
