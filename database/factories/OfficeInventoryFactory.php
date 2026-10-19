<?php

namespace Database\Factories;

use App\Models\InventoryItemVariant;
use App\Models\Office;
use App\Models\OfficeInventory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OfficeInventory>
 */
class OfficeInventoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'office_id' => Office::factory(),
            'inventory_item_variants_id' => InventoryItemVariant::factory(),
        ];
    }
}
