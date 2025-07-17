<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Str;

class CategoryProductSeeder extends Seeder
{
    public function run()
    {
        // Main Categories with Subcategories
        $categories = [
            [
                'name' => 'Electronics',
                'icon' => '🎮',
                'subcategories' => [
                    ['name' => 'Smartphones', 'icon' => '📱'],
                    ['name' => 'Laptops', 'icon' => '💻'],
                    ['name' => 'Tablets', 'icon' => '📱'],
                    ['name' => 'Headphones', 'icon' => '🎧'],
                    ['name' => 'Cameras', 'icon' => '📷'],
                    ['name' => 'Gaming', 'icon' => '🎮'],
                    ['name' => 'Smart Watches', 'icon' => '⌚'],
                    ['name' => 'Accessories', 'icon' => '🔌'],
                ]
            ],
            [
                'name' => 'Fashion',
                'icon' => '👕',
                'subcategories' => [
                    ['name' => 'Men\'s Clothing', 'icon' => '👔'],
                    ['name' => 'Women\'s Clothing', 'icon' => '👗'],
                    ['name' => 'Shoes', 'icon' => '👟'],
                    ['name' => 'Bags', 'icon' => '👜'],
                    ['name' => 'Watches', 'icon' => '⌚'],
                    ['name' => 'Jewelry', 'icon' => '💍'],
                    ['name' => 'Sunglasses', 'icon' => '🕶️'],
                ]
            ],
            [
                'name' => 'Home & Living',
                'icon' => '🏠',
                'subcategories' => [
                    ['name' => 'Furniture', 'icon' => '🪑'],
                    ['name' => 'Kitchen', 'icon' => '🍳'],
                    ['name' => 'Bedding', 'icon' => '🛏️'],
                    ['name' => 'Decor', 'icon' => '🖼️'],
                    ['name' => 'Lighting', 'icon' => '💡'],
                    ['name' => 'Storage', 'icon' => '📦'],
                    ['name' => 'Appliances', 'icon' => '🔌'],
                ]
            ],
            [
                'name' => 'Books',
                'icon' => '📚',
                'subcategories' => [
                    ['name' => 'Fiction', 'icon' => '📖'],
                    ['name' => 'Non-Fiction', 'icon' => '📚'],
                    ['name' => 'Children\'s Books', 'icon' => '📕'],
                    ['name' => 'Educational', 'icon' => '🎓'],
                    ['name' => 'Comics', 'icon' => '📔'],
                ]
            ],
            [
                'name' => 'Sports',
                'icon' => '⚽',
                'subcategories' => [
                    ['name' => 'Fitness Equipment', 'icon' => '🏋️'],
                    ['name' => 'Outdoor Sports', 'icon' => '🏃'],
                    ['name' => 'Team Sports', 'icon' => '⚽'],
                    ['name' => 'Cycling', 'icon' => '🚴'],
                    ['name' => 'Swimming', 'icon' => '🏊'],
                ]
            ],
            [
                'name' => 'Beauty',
                'icon' => '💄',
                'subcategories' => [
                    ['name' => 'Makeup', 'icon' => '💄'],
                    ['name' => 'Skincare', 'icon' => '🧴'],
                    ['name' => 'Hair Care', 'icon' => '💇'],
                    ['name' => 'Fragrances', 'icon' => '🌸'],
                    ['name' => 'Personal Care', 'icon' => '🧼'],
                ]
            ],
        ];

        $order = 0;
        foreach ($categories as $categoryData) {
            $category = Category::create([
                'name' => $categoryData['name'],
                'slug' => Str::slug($categoryData['name']),
                'icon' => $categoryData['icon'],
                'order' => $order++,
                'is_active' => true,
            ]);

            $subOrder = 0;
            foreach ($categoryData['subcategories'] as $subData) {
                $subcategory = Category::create([
                    'name' => $subData['name'],
                    'slug' => Str::slug($subData['name']),
                    'icon' => $subData['icon'],
                    'parent_id' => $category->id,
                    'order' => $subOrder++,
                    'is_active' => true,
                ]);

                // Create products for each subcategory
                $this->createProductsForCategory($subcategory);
            }
        }
    }

    private function createProductsForCategory($category)
    {
        $productTemplates = [
            'Smartphones' => [
                ['name' => 'iPhone 15 Pro Max', 'price' => 1199, 'compare_price' => 1299],
                ['name' => 'Samsung Galaxy S24 Ultra', 'price' => 1099, 'compare_price' => 1199],
                ['name' => 'Google Pixel 8 Pro', 'price' => 899, 'compare_price' => 999],
                ['name' => 'OnePlus 12', 'price' => 799, 'compare_price' => 899],
            ],
            'Laptops' => [
                ['name' => 'MacBook Pro 16"', 'price' => 2499, 'compare_price' => 2699],
                ['name' => 'Dell XPS 15', 'price' => 1799, 'compare_price' => 1999],
                ['name' => 'HP Spectre x360', 'price' => 1299, 'compare_price' => 1499],
                ['name' => 'Lenovo ThinkPad X1', 'price' => 1599, 'compare_price' => 1799],
            ],
            'Headphones' => [
                ['name' => 'AirPods Pro', 'price' => 249, 'compare_price' => 299],
                ['name' => 'Sony WH-1000XM5', 'price' => 349, 'compare_price' => 399],
                ['name' => 'Bose QuietComfort', 'price' => 299, 'compare_price' => 349],
                ['name' => 'Beats Studio Pro', 'price' => 279, 'compare_price' => 349],
            ],
        ];

        // Default products for categories without specific templates
        $defaultProducts = [
            ['name' => 'Premium ' . $category->name . ' Item 1', 'price' => 99, 'compare_price' => 149],
            ['name' => 'Deluxe ' . $category->name . ' Item 2', 'price' => 199, 'compare_price' => 249],
            ['name' => 'Professional ' . $category->name . ' Item 3', 'price' => 299, 'compare_price' => 399],
            ['name' => 'Standard ' . $category->name . ' Item 4', 'price' => 49, 'compare_price' => 79],
        ];

        $products = $productTemplates[$category->name] ?? $defaultProducts;

        foreach ($products as $index => $productData) {
            Product::create([
                'name' => $productData['name'],
                'slug' => Str::slug($productData['name']),
                'description' => 'High-quality ' . $productData['name'] . ' with advanced features and modern design. Perfect for everyday use and professional applications.',
                'price' => $productData['price'],
                'compare_price' => $productData['compare_price'],
                'quantity' => rand(10, 100),
                'sku' => 'SKU-' . strtoupper(Str::random(8)),
                'category_id' => $category->id,
                'is_featured' => $index < 2,
                'is_active' => true,
                'rating' => rand(35, 50) / 10,
                'reviews_count' => rand(10, 500),
            ]);
        }
    }
}