<?php

namespace Database\Seeders;

use App\Enums\InventoryLogAction;
use App\Models\InventoryItemVariant;
use App\Models\InventoryLog;
use App\Models\Office;
use App\Models\User;
use Illuminate\Database\Seeder;

class InventoryLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first() ?? User::factory()->create();
        $offices = Office::all();
        $variants = InventoryItemVariant::with('inventoryItem')->take(10)->get();

        if ($variants->isEmpty()) {
            return;
        }

        foreach ($variants as $variant) {
            $office = $offices->isNotEmpty() ? $offices->random() : null;
            $item = $variant->inventoryItem;

            // 1. Initial Inventory Added log
            InventoryLog::create([
                'action_type' => InventoryLogAction::InventoryAdded,
                'batch_id' => 'BATCH-INIT-'.str_pad((string) $variant->id, 4, '0', STR_PAD_LEFT),
                'reference_number' => 'PO-2026-'.str_pad((string) $variant->id, 4, '0', STR_PAD_LEFT),
                'user_id' => $user->id,
                'office_id' => null,
                'inventory_item_id' => $item?->id,
                'inventory_item_variant_id' => $variant->id,
                'item_name' => $item?->name,
                'variant_name' => $variant->variant_name,
                'quantity' => $variant->quantity,
                'quantity_before' => 0,
                'quantity_after' => $variant->quantity,
                'purpose' => 'Initial stock intake and cataloging',
                'remarks' => "Added {$variant->quantity} units of {$variant->variant_name} to warehouse inventory.",
            ]);

            // 2. Transfer log if office exists
            if ($office) {
                InventoryLog::create([
                    'action_type' => InventoryLogAction::Transfer,
                    'batch_id' => 'TRF-'.str_pad((string) $variant->id, 5, '0', STR_PAD_LEFT),
                    'reference_number' => 'PTR-2026-'.str_pad((string) $variant->id, 4, '0', STR_PAD_LEFT),
                    'user_id' => $user->id,
                    'office_id' => $office->id,
                    'inventory_item_id' => $item?->id,
                    'inventory_item_variant_id' => $variant->id,
                    'office_name' => $office->office_name,
                    'item_name' => $item?->name,
                    'variant_name' => $variant->variant_name,
                    'quantity' => 5,
                    'quantity_before' => $variant->quantity,
                    'quantity_after' => max(0, $variant->quantity - 5),
                    'purpose' => 'Office equipment & supply allotment',
                    'remarks' => "Transferred 5 units of {$variant->variant_name} to {$office->office_name}.",
                ]);

                // 3. Release log with spread dates
                $daysAgo = ($variant->id % 9);
                $releaseDate = now()->subDays($daysAgo)->setTime(rand(8, 16), rand(10, 55));
                InventoryLog::create([
                    'action_type' => InventoryLogAction::Release,
                    'batch_id' => 'REL-'.str_pad((string) $variant->id, 5, '0', STR_PAD_LEFT),
                    'reference_number' => 'RIS-2026-'.str_pad((string) $variant->id, 4, '0', STR_PAD_LEFT),
                    'user_id' => $user->id,
                    'office_id' => $office->id,
                    'inventory_item_id' => $item?->id,
                    'inventory_item_variant_id' => $variant->id,
                    'office_name' => $office->office_name,
                    'item_name' => $item?->name,
                    'variant_name' => $variant->variant_name,
                    'quantity' => rand(2, 6),
                    'quantity_before' => 10,
                    'quantity_after' => 10 - rand(2, 6),
                    'recipient' => ['Engr. J. Dela Cruz', 'Dr. Maria Santos', 'Officer R. Mendoza', 'Atty. L. Fernandez', 'Arch. V. Reyes'][$variant->id % 5],
                    'purpose' => ['Field survey and site operations', 'Department monthly supply replenishment', 'Emergency response logistics', 'Official administrative use', 'Project implementation support'][$variant->id % 5],
                    'remarks' => "Released stock of {$variant->variant_name} from {$office->office_name}.",
                    'created_at' => $releaseDate,
                    'updated_at' => $releaseDate,
                ]);
            }
        }
    }
}
