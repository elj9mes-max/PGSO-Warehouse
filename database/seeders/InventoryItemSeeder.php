<?php

namespace Database\Seeders;

use App\Models\InventoryItem;
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
                'name' => 'Standard Industrial Pallets',
                'description' => 'Heavy duty treated pine (1200 x 1000mm)',
                'category' => 'Hardware & Tools',
                'unit' => 'units',
                'quantity' => 42,
                'reorder_level' => 15,
                'unit_cost' => 1450.00,
                'location' => 'Aisle 3 • Bay B',
                'status' => 'In Stock',
            ],
            [
                'item_code' => 'ITM-8819',
                'name' => 'Archival Seal Tape Dispenser Rolls',
                'description' => 'Heavy duty clear sealing tape 48mm x 100m',
                'category' => 'Office Supplies',
                'unit' => 'rolls',
                'quantity' => 120,
                'reorder_level' => 30,
                'unit_cost' => 85.00,
                'location' => 'Aisle 1 • Bay A',
                'status' => 'In Stock',
            ],
            [
                'item_code' => 'ITM-8804',
                'name' => 'Wireless Barcode Scanners',
                'description' => '2D handheld scanner with USB cradle dock',
                'category' => 'IT Equipment',
                'unit' => 'units',
                'quantity' => 4,
                'reorder_level' => 8,
                'unit_cost' => 4800.00,
                'location' => 'Tech Cabinet',
                'status' => 'Low Stock',
            ],
            [
                'item_code' => 'ITM-8799',
                'name' => 'Disaster Response First Aid Kits',
                'description' => 'Compliant with LGU Emergency Services Spec',
                'category' => 'Medical Logistics',
                'unit' => 'kits',
                'quantity' => 75,
                'reorder_level' => 25,
                'unit_cost' => 2200.00,
                'location' => 'Aisle 4 • Bay C',
                'status' => 'In Stock',
            ],
            [
                'item_code' => 'ITM-8750',
                'name' => 'High-Visibility Reflective Vests',
                'description' => 'ANSI Class 2 reflective strips (Neon Yellow)',
                'category' => 'Safety Gear',
                'unit' => 'pieces',
                'quantity' => 210,
                'reorder_level' => 50,
                'unit_cost' => 320.00,
                'location' => 'Aisle 2 • Bay B',
                'status' => 'In Stock',
            ],
            [
                'item_code' => 'ITM-8712',
                'name' => 'Hydraulic Hand Pallet Trucks',
                'description' => 'Capacity: 3.0 Tons • Dual Polyurethane Wheels',
                'category' => 'Hardware & Tools',
                'unit' => 'units',
                'quantity' => 0,
                'reorder_level' => 4,
                'unit_cost' => 19500.00,
                'location' => 'Aisle 1 • Bay A',
                'status' => 'Out of Stock',
            ],
            [
                'item_code' => 'ITM-PEN-01',
                'name' => 'MyGel Signpen 0.5mm',
                'description' => 'Quick-drying high precision archival gel ink pen with tungsten carbide ball tip',
                'category' => 'Office Supplies',
                'unit' => 'pieces',
                'quantity' => 220,
                'reorder_level' => 30,
                'unit_cost' => 38.50,
                'location' => 'Aisle 1 • Shelf C-2',
                'status' => 'In Stock',
            ],
        ];

        foreach ($items as $item) {
            InventoryItem::updateOrCreate(
                ['item_code' => $item['item_code']],
                $item
            );
        }

        // Seed variants for MyGel Signpen 0.5mm
        $signpen = InventoryItem::where('item_code', 'ITM-PEN-01')->first();
        if ($signpen) {
            $variants = [
                [
                    'variant_name' => 'Blue',
                    'sku' => 'PEN-01-BLU',
                    'barcode' => '480088910011',
                    'variant_description' => 'Standard Royal Blue Gel Ink',
                    'unit' => 'pieces',
                    'quantity' => 100,
                    'reorder_level' => 15,
                    'unit_cost' => 38.50,
                    'location' => 'Aisle 1 • Shelf C-2',
                    'status' => 'In Stock',
                ],
                [
                    'variant_name' => 'Red',
                    'sku' => 'PEN-01-RED',
                    'barcode' => '480088910012',
                    'variant_description' => 'Correction & Auditing Red Gel Ink',
                    'unit' => 'pieces',
                    'quantity' => 40,
                    'reorder_level' => 15,
                    'unit_cost' => 38.50,
                    'location' => 'Aisle 1 • Shelf C-2',
                    'status' => 'In Stock',
                ],
                [
                    'variant_name' => 'Black',
                    'sku' => 'PEN-01-BLK',
                    'barcode' => '480088910013',
                    'variant_description' => 'Standard Document Black Gel Ink',
                    'unit' => 'pieces',
                    'quantity' => 80,
                    'reorder_level' => 15,
                    'unit_cost' => 38.50,
                    'location' => 'Aisle 1 • Shelf C-2',
                    'status' => 'In Stock',
                ],
            ];

            foreach ($variants as $variantData) {
                InventoryItemVariant::updateOrCreate(
                    ['sku' => $variantData['sku']],
                    array_merge($variantData, ['inventory_item_id' => $signpen->id])
                );
            }
        }
    }
}
