<?php

namespace Database\Seeders;

use App\Models\BidSession;
use App\Models\ConsumerProfile;
use App\Models\FarmerProfile;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin
        User::create([
            'name' => 'Admin Farmora',
            'email' => 'admin@farmora.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'approved',
            'phone' => '9999999999',
            'location' => 'New Delhi',
            'email_verified_at' => now(),
            'approved_at' => now(),
        ]);

        // Sample Farmers
        $farmerNames = ['Ravi Kumar', 'Sunita Devi', 'Mohan Patel'];
        $farmNames = ['Green Valley Farm', 'Sunrise Organics', 'Patel Farm Fresh'];
        $locations = ['Punjab', 'Maharashtra', 'Gujarat'];
        $produces = ['Wheat & Vegetables', 'Fruits & Herbs', 'Dairy & Grains'];

        foreach ($farmerNames as $index => $name) {
            $farmer = User::create([
                'name' => $name,
                'email' => strtolower(str_replace(' ', '.', $name)) . '@farmora.com',
                'password' => Hash::make('password'),
                'role' => 'farmer',
                'status' => 'approved',
                'phone' => '98' . rand(10000000, 99999999),
                'location' => $locations[$index],
                'email_verified_at' => now(),
                'approved_at' => now(),
            ]);

            FarmerProfile::create([
                'user_id' => $farmer->id,
                'farm_name' => $farmNames[$index],
                'farm_size' => rand(5, 50) . ' acres',
                'location' => $locations[$index],
                'bio' => "Experienced farmer from {$locations[$index]} specializing in {$produces[$index]}.",
                'primary_produce' => $produces[$index],
            ]);

            // Create products for each farmer
            $this->createProductsForFarmer($farmer, $index);
        }

        // Sample Consumers
        $consumerNames = ['Priya Sharma', 'Amit Singh', 'Neha Gupta'];
        $addresses = ['Mumbai, Maharashtra', 'Delhi, NCR', 'Bangalore, Karnataka'];

        foreach ($consumerNames as $index => $name) {
            $consumer = User::create([
                'name' => $name,
                'email' => strtolower(str_replace(' ', '.', $name)) . '@farmora.com',
                'password' => Hash::make('password'),
                'role' => 'consumer',
                'status' => 'approved',
                'phone' => '97' . rand(10000000, 99999999),
                'location' => $addresses[$index],
                'email_verified_at' => now(),
                'approved_at' => now(),
            ]);

            ConsumerProfile::create([
                'user_id' => $consumer->id,
                'delivery_address' => $addresses[$index],
                'preferred_categories' => [1, 2, 3],
            ]);
        }

        // A pending farmer for admin testing
        $pendingFarmer = User::create([
            'name' => 'Rajesh Verma',
            'email' => 'rajesh.verma@farmora.com',
            'password' => Hash::make('password'),
            'role' => 'farmer',
            'status' => 'pending',
            'phone' => '9612345678',
            'location' => 'Rajasthan',
            'email_verified_at' => now(),
        ]);

        FarmerProfile::create([
            'user_id' => $pendingFarmer->id,
            'farm_name' => 'Desert Bloom Farm',
            'farm_size' => '15 acres',
            'location' => 'Rajasthan',
            'primary_produce' => 'Spices & Herbs',
        ]);
    }

    private function createProductsForFarmer(User $farmer, int $index): void
    {
        $products = [
            [
                ['title' => 'Fresh Organic Tomatoes', 'desc' => 'Hand-picked organic tomatoes from our pesticide-free fields. Grown with natural compost and harvested at peak ripeness for maximum flavor and nutrition.', 'cat' => 1, 'qty' => 100, 'unit' => 'kg', 'price' => 45, 'type' => 'both'],
                ['title' => 'Golden Wheat Grain', 'desc' => 'Premium quality wheat grain from the fertile plains of Punjab. Naturally sun-dried and ready for grinding into fresh flour.', 'cat' => 3, 'qty' => 500, 'unit' => 'kg', 'price' => 35, 'type' => 'buy'],
                ['title' => 'Green Spinach Bundle', 'desc' => 'Freshly harvested spinach bundles, rich in iron and vitamins. Grown using traditional farming methods.', 'cat' => 1, 'qty' => 50, 'unit' => 'kg', 'price' => 30, 'type' => 'buy'],
            ],
            [
                ['title' => 'Sweet Alphonso Mangoes', 'desc' => 'The king of mangoes! Premium Alphonso mangoes from Ratnagiri, Maharashtra. Naturally ripened and incredibly sweet.', 'cat' => 2, 'qty' => 200, 'unit' => 'dozen', 'price' => 800, 'type' => 'bid'],
                ['title' => 'Fresh Basil & Mint', 'desc' => 'Aromatic fresh basil and mint leaves, perfect for teas, chutneys, and gourmet cooking.', 'cat' => 5, 'qty' => 30, 'unit' => 'kg', 'price' => 120, 'type' => 'buy'],
                ['title' => 'Organic Strawberries', 'desc' => 'Sweet and juicy organic strawberries grown in our polyhouse. No pesticides, just pure natural goodness.', 'cat' => 2, 'qty' => 75, 'unit' => 'kg', 'price' => 250, 'type' => 'both'],
            ],
            [
                ['title' => 'Pure Farm Butter', 'desc' => 'Traditional homemade butter from grass-fed cows. Rich, creamy, and full of natural flavor.', 'cat' => 4, 'qty' => 50, 'unit' => 'kg', 'price' => 500, 'type' => 'buy'],
                ['title' => 'Basmati Rice Premium', 'desc' => 'Long-grain premium Basmati rice aged for 2 years. Perfect aroma and elongation on cooking.', 'cat' => 3, 'qty' => 1000, 'unit' => 'kg', 'price' => 95, 'type' => 'bid'],
                ['title' => 'Fresh Turmeric Root', 'desc' => 'Raw organic turmeric roots with high curcumin content. Ideal for cooking and medicinal use.', 'cat' => 8, 'qty' => 80, 'unit' => 'kg', 'price' => 150, 'type' => 'both'],
            ],
        ];

        foreach ($products[$index] as $p) {
            $product = Product::create([
                'farmer_id' => $farmer->id,
                'category_id' => $p['cat'],
                'title' => $p['title'],
                'description' => $p['desc'],
                'quantity' => $p['qty'],
                'unit' => $p['unit'],
                'price' => $p['type'] !== 'bid' ? $p['price'] : null,
                'listing_type' => $p['type'],
                'status' => 'active',
                'harvest_date' => now()->subDays(rand(1, 5)),
                'origin_location' => $farmer->location,
                'is_available' => true,
                'views_count' => rand(10, 300),
            ]);

            // Create placeholder image record
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => 'products/placeholder.jpg',
                'is_primary' => true,
            ]);

            // Create bid sessions for biddable products
            if (in_array($p['type'], ['bid', 'both'])) {
                BidSession::create([
                    'product_id' => $product->id,
                    'start_price' => $p['price'] ?? $p['qty'] * 10,
                    'min_increment' => max(10, ($p['price'] ?? 100) * 0.05),
                    'start_time' => now(),
                    'end_time' => now()->addDays(rand(2, 7)),
                    'status' => 'active',
                ]);
            }
        }
    }
}
