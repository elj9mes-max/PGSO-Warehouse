<?php

namespace Database\Factories;

use App\Models\InventoryItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<InventoryItem>
 */
class InventoryItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'item_code' => 'ITM-'.fake()->unique()->numerify('####'),
            'name' => fake()->words(3, true),
            'barcode' => 'BC-'.fake()->unique()->numerify('##########'),
            'category' => fake()->randomElement(['Hardware & Tools', 'Office Supplies', 'Medical Logistics', 'IT Equipment', 'Safety Gear']),
            'unit' => fake()->randomElement(['units', 'pieces', 'boxes', 'rolls', 'sets', 'kits', 'reams']),
            'quantity' => fake()->numberBetween(25, 300),
            'available_for_transfer' => fake()->numberBetween(25, 300),
            'reorder_level' => fake()->numberBetween(5, 20),
            'unit_cost' => fake()->randomFloat(2, 50, 25000),
            'location' => 'Aisle '.fake()->numberBetween(1, 6).' • Bay '.fake()->randomElement(['A', 'B', 'C', 'D']),
            'status' => 'In Stock',
            'is_for_inspection' => false,
        ];
    }

    /**
     * Indicate that the item is low on stock.
     */
    public function lowStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'quantity' => 3,
            'available_for_transfer' => 3,
            'reorder_level' => 10,
            'status' => 'Low Stock',
        ]);
    }

    /**
     * Indicate that the item is out of stock.
     */
    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'quantity' => 0,
            'available_for_transfer' => 0,
            'reorder_level' => 10,
            'status' => 'Out of Stock',
        ]);
    }
}
