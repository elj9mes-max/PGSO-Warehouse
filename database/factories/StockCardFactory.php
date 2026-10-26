<?php

namespace Database\Factories;

use App\Models\StockCard;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StockCard>
 */
class StockCardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'office' => 'ALL OFFICES',
            'item_name' => fake()->words(3, true),
            'unit' => fake()->randomElement(['pc', 'box', 'roll', 'unit', 'pack']),
            'stock_no' => 'SKU-'.fake()->numerify('####'),
            'card_no' => 'Card #'.fake()->randomDigitNotNull(),
        ];
    }
}
