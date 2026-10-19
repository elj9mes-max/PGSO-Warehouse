<?php

namespace Database\Factories;

use App\Models\InventoryItem;
use App\Models\InventoryItemVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<InventoryItemVariant>
 */
class InventoryItemVariantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $quantity = fake()->numberBetween(10, 100);

        return [
            'inventory_item_id' => InventoryItem::factory(),
            'variant_name' => fake()->word().' Edition',
            'barcode' => 'BAR-'.fake()->unique()->numerify('########'),
            'sku' => 'SKU-'.fake()->unique()->bothify('??-####'),
            'variant_description' => fake()->sentence(),
            'unit' => 'pcs',
            'quantity' => $quantity,
            'available_for_transfer' => $quantity,
            'reorder_level' => 5,
            'unit_cost' => fake()->randomFloat(2, 50, 5000),
            'location' => 'Bay 1',
            'status' => 'In Stock',
            'is_for_inspection' => false,
        ];
    }

    /**
     * Indicate that the variant is pending inspection.
     */
    public function forInspection(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_for_inspection' => true,
        ]);
    }
}
