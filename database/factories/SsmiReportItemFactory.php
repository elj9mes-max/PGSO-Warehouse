<?php

namespace Database\Factories;

use App\Models\SsmiReport;
use App\Models\SsmiReportItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SsmiReportItem>
 */
class SsmiReportItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $qty = fake()->numberBetween(1, 100);
        $cost = fake()->randomFloat(2, 10, 500);

        return [
            'ssmi_report_id' => SsmiReport::factory(),
            'row_order' => 1,
            'stock_no' => 'SKU-'.fake()->numerify('####'),
            'description' => fake()->words(3, true),
            'unit' => fake()->randomElement(['reams', 'pcs.', 'box', 'pack', 'units']),
            'ris_quantities' => [$qty, '', '', '', '', '', '', ''],
            'total_quantity' => $qty,
            'unit_cost' => $cost,
            'total_cost' => $qty * $cost,
        ];
    }
}
