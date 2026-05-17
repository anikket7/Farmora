<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Vegetables', 'slug' => 'vegetables', 'icon' => '🥬', 'color' => '#22c55e'],
            ['name' => 'Fruits', 'slug' => 'fruits', 'icon' => '🍎', 'color' => '#ef4444'],
            ['name' => 'Grains', 'slug' => 'grains', 'icon' => '🌾', 'color' => '#eab308'],
            ['name' => 'Dairy', 'slug' => 'dairy', 'icon' => '🥛', 'color' => '#3b82f6'],
            ['name' => 'Herbs', 'slug' => 'herbs', 'icon' => '🌿', 'color' => '#10b981'],
            ['name' => 'Organic', 'slug' => 'organic', 'icon' => '🌱', 'color' => '#84cc16'],
            ['name' => 'Pulses', 'slug' => 'pulses', 'icon' => '🫘', 'color' => '#f97316'],
            ['name' => 'Spices', 'slug' => 'spices', 'icon' => '🌶️', 'color' => '#dc2626'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
