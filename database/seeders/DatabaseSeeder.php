<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SaasBanner;
use App\Models\SaasCategory;
use App\Models\SaasProduct;
use App\Models\SaasBrand;
use App\Models\SaasFlashDeal;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            SaasUserSeeder::class,
        ]);

        // Create test admin user
        User::firstOrCreate([
            'email' => 'admin@allsewa.com'
        ], [
            'name' => 'Admin User',
            'password' => bcrypt('password'),
            'user_type' => 'admin'
        ]);

        // Create sample banners
        $banners = [
            [
                'title' => 'Summer Sale 2024',
                'image' => 'banners/summer-sale.jpg',
                'link_url' => '/shop',
                'position' => 'main_section',
                'is_active' => true
            ],
            [
                'title' => 'Electronics Collection',
                'image' => 'banners/electronics.jpg',
                'link_url' => '/category/electronics',
                'position' => 'footer',
                'is_active' => true
            ],
            [
                'title' => 'Fashion Week Popup',
                'image' => 'banners/fashion.jpg',
                'link_url' => '/category/fashion',
                'position' => 'popup',
                'is_active' => true
            ]
        ];

        foreach ($banners as $banner) {
            SaasBanner::firstOrCreate(['title' => $banner['title']], $banner);
        }

        // Create sample brands
        $brands = [
            ['name' => 'Samsung', 'slug' => 'samsung'],
            ['name' => 'Apple', 'slug' => 'apple'],
            ['name' => 'Nike', 'slug' => 'nike'],
            ['name' => 'Adidas', 'slug' => 'adidas'],
            ['name' => 'Sony', 'slug' => 'sony'],
            ['name' => 'HP', 'slug' => 'hp'],
            ['name' => 'Dell', 'slug' => 'dell'],
            ['name' => 'Canon', 'slug' => 'canon']
        ];

        foreach ($brands as $brand) {
            SaasBrand::firstOrCreate(['slug' => $brand['slug']], $brand);
        }

        // Create sample categories
        $categories = [
            [
                'name' => 'Electronics',
                'slug' => 'electronics',
                'featured' => 1,
                'status' => 1
            ],
            [
                'name' => 'Fashion',
                'slug' => 'fashion',
                'featured' => 1,
                'status' => 1
            ],
            [
                'name' => 'Home & Garden',
                'slug' => 'home-garden',
                'featured' => 1,
                'status' => 1
            ],
            [
                'name' => 'Sports & Outdoors',
                'slug' => 'sports-outdoors',
                'featured' => 1,
                'status' => 1
            ],
            [
                'name' => 'Books',
                'slug' => 'books',
                'featured' => 1,
                'status' => 1
            ],
            [
                'name' => 'Beauty & Health',
                'slug' => 'beauty-health',
                'featured' => 1,
                'status' => 1
            ],
            [
                'name' => 'Toys & Games',
                'slug' => 'toys-games',
                'featured' => 1,
                'status' => 1
            ],
            [
                'name' => 'Automotive',
                'slug' => 'automotive',
                'featured' => 1,
                'status' => 1
            ]
        ];

        foreach ($categories as $category) {
            SaasCategory::firstOrCreate(['slug' => $category['slug']], $category);
        }

        // Create sample products
        $electronics = SaasCategory::where('slug', 'electronics')->first();
        $fashion = SaasCategory::where('slug', 'fashion')->first();
        $samsung = SaasBrand::where('slug', 'samsung')->first();
        $apple = SaasBrand::where('slug', 'apple')->first();
        $nike = SaasBrand::where('slug', 'nike')->first();

        $products = [
            [
                'name' => 'Samsung Galaxy S24',
                'slug' => 'samsung-galaxy-s24',
                'category_id' => $electronics?->id,
                'brand_id' => $samsung?->id,
                'price' => 999.99,
                'description' => 'Latest Samsung flagship smartphone with amazing features.',
                'short_description' => 'Premium smartphone with advanced camera system.',
                'SKU' => 'SGS24001',
                'stock' => 50,
                'is_featured' => 1,
                'is_active' => 1,
                'meta_title' => 'Samsung Galaxy S24 - Latest Flagship Smartphone',
                'meta_description' => 'Buy Samsung Galaxy S24 with latest features and fast delivery.'
            ],
            [
                'name' => 'iPhone 15 Pro',
                'slug' => 'iphone-15-pro',
                'category_id' => $electronics?->id,
                'brand_id' => $apple?->id,
                'price' => 1199.99,
                'description' => 'Apple iPhone 15 Pro with titanium design and A17 Pro chip.',
                'short_description' => 'Pro smartphone with titanium build and advanced camera.',
                'SKU' => 'IP15P001',
                'stock' => 30,
                'is_featured' => 1,
                'is_active' => 1,
                'meta_title' => 'iPhone 15 Pro - Apple Premium Smartphone',
                'meta_description' => 'Get the latest iPhone 15 Pro with fast shipping and warranty.'
            ],
            [
                'name' => 'Nike Air Max 270',
                'slug' => 'nike-air-max-270',
                'category_id' => $fashion?->id,
                'brand_id' => $nike?->id,
                'price' => 129.99,
                'description' => 'Comfortable running shoes with Air Max technology.',
                'short_description' => 'Popular Nike running shoes with superior comfort.',
                'SKU' => 'NAM270001',
                'stock' => 75,
                'is_featured' => 1,
                'is_active' => 1,
                'meta_title' => 'Nike Air Max 270 - Running Shoes',
                'meta_description' => 'Shop Nike Air Max 270 running shoes with Air Max comfort technology.'
            ],
            [
                'name' => 'MacBook Air M2',
                'slug' => 'macbook-air-m2',
                'category_id' => $electronics?->id,
                'brand_id' => $apple?->id,
                'price' => 1099.99,
                'description' => 'Apple MacBook Air with M2 chip for incredible performance.',
                'short_description' => 'Lightweight laptop with powerful M2 processor.',
                'SKU' => 'MBA2001',
                'stock' => 25,
                'is_featured' => 1,
                'is_active' => 1,
                'meta_title' => 'MacBook Air M2 - Apple Laptop',
                'meta_description' => 'Buy MacBook Air M2 with fast performance and long battery life.'
            ],
            [
                'name' => 'Samsung 55" QLED TV',
                'slug' => 'samsung-55-qled-tv',
                'category_id' => $electronics?->id,
                'brand_id' => $samsung?->id,
                'price' => 799.99,
                'description' => '55-inch QLED TV with 4K resolution and smart features.',
                'short_description' => 'Premium QLED TV with stunning 4K picture quality.',
                'SKU' => 'STV55Q001',
                'stock' => 20,
                'is_featured' => 1,
                'is_active' => 1,
                'meta_title' => 'Samsung 55" QLED TV - 4K Smart TV',
                'meta_description' => 'Experience amazing picture quality with Samsung QLED TV.'
            ],
            [
                'name' => 'Nike Dri-FIT T-Shirt',
                'slug' => 'nike-dri-fit-tshirt',
                'category_id' => $fashion?->id,
                'brand_id' => $nike?->id,
                'price' => 29.99,
                'description' => 'Moisture-wicking t-shirt perfect for workouts and daily wear.',
                'short_description' => 'Comfortable athletic t-shirt with Dri-FIT technology.',
                'SKU' => 'NDT001',
                'stock' => 100,
                'is_featured' => 1,
                'is_active' => 1,
                'meta_title' => 'Nike Dri-FIT T-Shirt - Athletic Wear',
                'meta_description' => 'Stay comfortable with Nike Dri-FIT moisture-wicking t-shirt.'
            ]
        ];

        foreach ($products as $product) {
            SaasProduct::firstOrCreate(['slug' => $product['slug']], $product);
        }

        // Create a sample flash deal
        $flashDeal = SaasFlashDeal::firstOrCreate([
            'title' => 'Electronics Flash Sale'
        ], [
            'title' => 'Electronics Flash Sale',
            'start_time' => now()->subHour(),
            'end_time' => now()->addDays(7),
            'banner_image' => 'flash_deals/electronics-sale.jpg'
        ]);

        // Attach products to flash deal
        if ($flashDeal && $electronics) {
            $electronicsProducts = SaasProduct::where('category_id', $electronics->id)->take(3)->get();
            foreach ($electronicsProducts as $product) {
                $flashDeal->products()->syncWithoutDetaching([
                    $product->id => [
                        'discount_type' => 'percentage',
                        'discount_value' => rand(10, 30)
                    ]
                ]);
            }
        }

        $this->command->info('Sample data created successfully!');
    }
}
