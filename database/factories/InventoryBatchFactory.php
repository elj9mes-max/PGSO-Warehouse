<?php

namespace Database\Factories;

use App\Models\InventoryBatch;
use App\Models\InventoryItem;
use App\Models\InventoryItemVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<InventoryBatch>
 */
class InventoryBatchFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $qty = fake()->numberBetween(10, 100);

        return [
            'batch_number' => 'BATCH-'.fake()->unique()->numerify('#####'),
            'inventory_item_id' => InventoryItem::factory(),
            'inventory_item_variant_id' => InventoryItemVariant::factory(),
            'office_id' => null,
            'po_number' => 'PO-'.fake()->numerify('2026-####'),
            'pr_number' => 'PR-'.fake()->numerify('2026-####'),
            'supplier' => fake()->company(),
            'delivery_date' => fake()->date(),
            'unit_cost' => fake()->randomFloat(2, 10, 500),
            'quantity_received' => $qty,
            'quantity_remaining' => $qty,
            'status' => 'active',
            'remarks' => fake()->sentence(),
        ];
    }
}
