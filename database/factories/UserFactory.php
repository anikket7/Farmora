<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected static ?string $password = null;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
            'role' => 'consumer',
            'status' => 'approved',
            'phone' => fake()->numerify('##########'),
            'location' => fake()->city(),
            'remember_token' => Str::random(10),
        ];
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
            'status' => 'approved',
        ]);
    }

    public function farmer(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'farmer',
            'status' => 'approved',
            'approved_at' => now(),
        ]);
    }

    public function consumer(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'consumer',
            'status' => 'approved',
            'approved_at' => now(),
        ]);
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'approved_at' => null,
        ]);
    }
}
