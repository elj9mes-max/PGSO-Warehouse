<?php

namespace Database\Factories;

use App\Models\Office;
use App\Models\RequisitionRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RequisitionRequest>
 */
class RequisitionRequestFactory extends Factory
{
    protected $model = RequisitionRequest::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'request_number' => 'RIS-'.date('Ym').'-'.fake()->unique()->numerify('####'),
            'request_type' => 'RIS',
            'office_id' => Office::factory(),
            'user_id' => User::factory(),
            'status' => 'pending',
            'purpose' => fake()->sentence(),
            'division' => 'Administrative Division',
            'responsibility_center_code' => '1061',
            'ris_no' => 'RIS-'.fake()->numerify('####'),
            'sai_no' => 'SAI-'.fake()->numerify('####'),
            'air_number' => null,
            'po_number' => null,
            'supplier' => null,
            'requested_by_name' => fake()->name(),
            'requested_by_designation' => 'Staff Officer',
            'approved_by_id' => null,
            'approved_by_name' => 'RICARDO Q. BAUTISTA',
            'approved_by_designation' => 'PGDH-PGSO',
            'approved_at' => null,
            'issued_by_id' => null,
            'issued_by_name' => 'RAYMOND G. AMAT',
            'issued_by_designation' => 'AA-III',
            'issued_at' => null,
            'received_by_id' => null,
            'received_by_name' => 'IMEE SALAMANCA',
            'received_by_designation' => 'AA-I',
            'received_at' => null,
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
        ]);
    }

    /**
     * Indicate that the request is issued.
     */
    public function issued(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'issued',
            'approved_at' => now()->subHour(),
            'issued_at' => now(),
            'received_at' => now(),
        ]);
    }

    /**
     * Indicate that the request is a Purchase Request (PR).
     */
    public function purchaseRequest(): static
    {
        return $this->state(fn (array $attributes) => [
            'request_type' => 'PR',
            'request_number' => 'PR-'.date('Ym').'-'.fake()->unique()->numerify('####'),
        ]);
    }
}
