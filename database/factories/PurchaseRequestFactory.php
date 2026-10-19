<?php

namespace Database\Factories;

use App\Models\Office;
use App\Models\PurchaseRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PurchaseRequest>
 */
class PurchaseRequestFactory extends Factory
{
    protected $model = PurchaseRequest::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'pr_number' => 'PR-'.date('Ym').'-'.fake()->unique()->numerify('####'),
            'office_id' => Office::factory(),
            'user_id' => User::factory(),
            'status' => 'pending',
            'purpose' => fake()->sentence(),
            'responsibility_center_code' => '1061',
            'requested_by_name' => fake()->name(),
            'requested_by_designation' => 'Staff Officer',
            'approved_by_id' => null,
            'approved_by_name' => null,
            'approved_by_designation' => null,
            'approved_at' => null,
            'total_estimated_cost' => 0.00,
            'remarks' => null,
            'rejection_reason' => null,
        ];
    }

    /**
     * Indicate that the request is approved.
     */
    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by_name' => 'Department Head',
            'approved_by_designation' => 'Approving Authority',
        ]);
    }
}
