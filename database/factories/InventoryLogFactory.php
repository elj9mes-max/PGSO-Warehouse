<?php

namespace Database\Factories;

use App\Enums\InventoryLogAction;
use App\Models\InventoryItem;
use App\Models\InventoryItemVariant;
use App\Models\InventoryLog;
use App\Models\Office;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<InventoryLog>
 */
class InventoryLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'action_type' => fake()->randomElement(InventoryLogAction::cases()),
            'batch_id' => 'BATCH-'.fake()->numerify('#####'),
            'reference_number' => 'REF-'.fake()->bothify('??-####'),
            'user_id' => User::factory(),
            'office_id' => Office::factory(),
            'inventory_item_id' => InventoryItem::factory(),
            'inventory_item_variant_id' => InventoryItemVariant::factory(),
            'office_name' => fake()->company().' Department',
            'item_name' => fake()->words(3, true),
            'variant_name' => fake()->word().' Edition',
            'quantity' => fake()->numberBetween(1, 50),
            'quantity_before' => fake()->numberBetween(50, 100),
            'quantity_after' => fake()->numberBetween(0, 50),
            'recipient' => fake()->name(),
            'purpose' => fake()->sentence(4),
            'old_values' => null,
            'new_values' => null,
            'remarks' => fake()->sentence(),
            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
        ];
    }

    /**
     * State for a stock transfer.
     */
    public function transfer(): static
    {
        return $this->state(fn () => [
            'action_type' => InventoryLogAction::Transfer,
            'purpose' => 'Office distribution and replenishment',
        ]);
    }

    /**
     * State for a stock release.
     */
    public function release(): static
    {
        return $this->state(fn () => [
            'action_type' => InventoryLogAction::Release,
            'recipient' => fake()->name(),
            'purpose' => 'Official requisition and usage',
        ]);
    }

    /**
     * State for an item edit.
     */
    public function itemEdit(): static
    {
        return $this->state(fn () => [
            'action_type' => InventoryLogAction::ItemUpdated,
            'office_id' => null,
            'office_name' => null,
            'recipient' => null,
            'old_values' => ['unit_cost' => 150.00, 'reorder_level' => 5],
            'new_values' => ['unit_cost' => 180.00, 'reorder_level' => 10],
            'remarks' => 'Updated unit cost and reorder threshold',
        ]);
    }

    /**
     * State for adding inventory.
     */
    public function inventoryAdded(): static
    {
        return $this->state(fn () => [
            'action_type' => InventoryLogAction::InventoryAdded,
            'office_id' => null,
            'office_name' => null,
            'recipient' => null,
            'purpose' => 'Supplier delivery receiving',
            'remarks' => 'Restocked inventory from purchase order',
        ]);
    }
}
