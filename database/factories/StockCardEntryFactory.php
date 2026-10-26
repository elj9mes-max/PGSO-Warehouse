<?php

namespace Database\Factories;

use App\Models\StockCard;
use App\Models\StockCardEntry;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StockCardEntry>
 */
class StockCardEntryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'stock_card_id' => StockCard::factory(),
            'entry_date' => fake()->date(),
            'reference' => 'PO #'.fake()->numerify('2026-###'),
            'receipt_qty' => fake()->randomElement([10, 20, 50]),
            'issue_to' => null,
            'issuance_qty' => null,
            'balance_qty' => 50,
            'remarks' => fake()->sentence(4),
            'row_order' => 1,
        ];
    }
}
