<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $listingType = fake()->randomElement(['buy', 'bid', 'both']);

        return [
            'farmer_id' => User::factory()->farmer(),
            'category_id' => Category::factory(),
            'title' => fake()->randomElement([
                'Fresh Organic Tomatoes', 'Farm-Fresh Eggs', 'Sweet Mangoes',
                'Green Spinach Bundle', 'Pure Cow Milk', 'Golden Wheat Grain',
                'Red Onions', 'Basmati Rice', 'Fresh Coriander', 'Yellow Lentils',
                'Organic Potatoes', 'Fresh Turmeric Root', 'Green Chillies',
                'Farm Butter', 'Fresh Garlic Bulbs',
            ]),
            'description' => fake()->paragraphs(2, true),
            'quantity' => fake()->randomFloat(1, 5, 500),
            'unit' => fake()->randomElement(['kg', 'gram', 'dozen', 'piece', 'liter']),
            'price' => $listingType !== 'bid' ? fake()->randomFloat(2, 20, 5000) : null,
            'listing_type' => $listingType,
            'status' => 'active',
            'harvest_date' => fake()->dateTimeBetween('-7 days', '+7 days'),
            'origin_location' => fake()->city(),
            'is_available' => true,
            'views_count' => fake()->numberBetween(0, 500),
        ];
    }
}
