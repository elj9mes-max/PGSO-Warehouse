<?php

namespace Database\Factories;

use App\Models\InventoryItemVariant;
use App\Models\RequisitionRequest;
use App\Models\RequisitionRequestItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RequisitionRequestItem>
 */
class RequisitionRequestItemFactory extends Factory
{
    protected $model = RequisitionRequestItem::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $quantity = fake()->numberBetween(1, 20);
        $unitCost = fake()->randomFloat(2, 50, 500);

        return [
            'requisition_request_id' => RequisitionRequest::factory(),
            'inventory_item_variant_id' => InventoryItemVariant::factory(),
            'stock_no' => 'SKU-'.strtoupper(fake()->bothify('???-###')),
            'item_name' => fake()->words(3, true),
            'variant_name' => fake()->word(),
            'description' => fake()->sentence(),
            'unit' => fake()->randomElement(['pcs', 'boxes', 'packs', 'reams']),
            'quantity_requested' => $quantity,
            'quantity_issued' => 0,
            'unit_cost' => $unitCost,
            'total_cost' => round($quantity * $unitCost, 2),
            'remarks' => null,
            'status' => 'pending',
        ];
    }

    /**
     * Indicate that the item is issued.
     */
    public function issued(): static
    {
        return $this->state(fn (array $attributes) => [
            'quantity_issued' => $attributes['quantity_requested'] ?? 1,
            'status' => 'issued',
        ]);
    }
}
