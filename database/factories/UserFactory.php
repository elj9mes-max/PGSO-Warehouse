<?php

namespace Database\Factories;

use App\Enums\AccountLevel;
use App\Models\Office;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $firstName = fake()->firstName();
        $lastName = fake()->lastName();

        return [
            'first_name' => $firstName,
            'middle_initial' => fake()->randomLetter().'.',
            'last_name' => $lastName,
            'username' => fake()->unique()->userName(),
            'name' => "{$firstName} {$lastName}",
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'account_level' => AccountLevel::Staff,
            'office_id' => null,
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the user is an administrator.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'account_level' => AccountLevel::Admin,
        ]);
    }

    /**
     * Indicate that the user is a warehouse staff member.
     */
    public function staff(): static
    {
        return $this->state(fn (array $attributes) => [
            'account_level' => AccountLevel::Staff,
        ]);
    }

    /**
     * Indicate that the user belongs to a specific office.
     */
    public function forOffice(Office|int $office): static
    {
        return $this->state(fn (array $attributes) => [
            'office_id' => $office instanceof Office ? $office->id : $office,
        ]);
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
