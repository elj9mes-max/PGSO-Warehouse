<?php

namespace Database\Factories;

use App\Models\SsmiReport;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SsmiReport>
 */
class SsmiReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'report_no' => 'SSMI-'.date('Y-m').'-'.fake()->unique()->numerify('####'),
            'period_text' => fake()->date('m/d/Y'),
            'period_date' => fake()->date(),
            'ris_columns' => [
                'RIS-'.fake()->numerify('####'),
                'RIS-'.fake()->numerify('####'),
                'RIS-'.fake()->numerify('####'),
                '', '', '', '', '',
            ],
            'total_amount' => fake()->randomFloat(2, 1000, 50000),
            'status' => 'draft',
            'prepared_by_name' => 'Raymond G. Amat',
            'prepared_by_designation' => 'AA-III Warehouse',
            'approved_by_name' => 'Joel Friginal',
            'approved_by_designation' => 'SAO',
        ];
    }
}
