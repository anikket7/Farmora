<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->randomElement([
            'Vegetables', 'Fruits', 'Grains', 'Dairy',
            'Herbs', 'Organic', 'Pulses', 'Spices',
        ]);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'icon' => '🌿',
            'color' => fake()->hexColor(),
            'is_active' => true,
        ];
    }
}
