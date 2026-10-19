<?php

namespace Database\Factories;

use App\Models\InventoryItemVariant;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PurchaseRequestItem>
 */
class PurchaseRequestItemFactory extends Factory
{
    protected $model = PurchaseRequestItem::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $qty = fake()->numberBetween(1, 20);
        $cost = fake()->randomFloat(2, 50, 1000);

        return [
            'purchase_request_id' => PurchaseRequest::factory(),
            'inventory_item_variant_id' => InventoryItemVariant::factory(),
            'item_code' => 'ITM-'.fake()->unique()->numerify('####'),
            'barcode' => 'BAR-'.fake()->unique()->numerify('######'),
            'item_name' => fake()->words(3, true),
            'variant_name' => fake()->words(2, true),
            'description' => fake()->sentence(),
            'unit' => 'pcs',
            'quantity_requested' => $qty,
            'estimated_unit_cost' => $cost,
            'estimated_total_cost' => round($qty * $cost, 2),
            'remarks' => null,
        ];
    }
}
