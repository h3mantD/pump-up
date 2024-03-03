<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Database\Seeder;

final class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Treadmill',
                'description' => 'A treadmill is a device generally for walking, running, or climbing while staying in the same place.',
                'price' => 1000.00,
                'stock' => 10,
                'status' => 'available',
                'image' => null,
                'category_id' => 1,
            ],
            [
                'name' => 'Dumbbell',
                'description' => 'A dumbbell is a short bar with a weight at each end, used typically in pairs for exercise or muscle-building.',
                'price' => 100.00,
                'stock' => 100,
                'status' => 'available',
                'image' => null,
                'category_id' => 2,
            ],
            [
                'name' => 'Gym Package',
                'description' => 'A gym package is a set of gym equipment that is sold together.',
                'price' => 10000.00,
                'stock' => 5,
                'status' => 'available',
                'image' => null,
                'category_id' => 3,
            ],
            [
                'name' => 'Bench Press',
                'description' => 'The bench press is an upper-body weight training exercise in which the trainee presses a weight upwards while lying on a weight training bench.',
                'price' => 500.00,
                'stock' => 20,
                'status' => 'available',
                'image' => null,
                'category_id' => 4,
            ],
            [
                'name' => 'Gym Gloves',
                'description' => 'Gym gloves are used to protect the hands from developing corns and calluses.',
                'price' => 10.00,
                'stock' => 200,
                'status' => 'available',
                'image' => null,
                'category_id' => 5,
            ],
            [
                'name' => 'Gym Towel',
                'description' => 'A gym towel is a towel used to wipe away sweat during a workout.',
                'price' => 5.00,
                'stock' => 200,
                'status' => 'available',
                'image' => null,
                'category_id' => 6,
            ],
            [
                'name' => 'Gym Bag',
                'description' => 'A gym bag is a bag used to carry gym equipment and items.',
                'price' => 20.00,
                'stock' => 100,
                'status' => 'available',
                'image' => null,
                'category_id' => 6,
            ],
            [
                'name' => 'Gym Shoes',
                'description' => 'Gym shoes are shoes specifically designed for gym activities.',
                'price' => 50.00,
                'stock' => 100,
                'status' => 'available',
                'image' => null,
                'category_id' => 6,
            ],
        ];

        foreach ($products as $product) {
            $product = Product::create($product);
            Review::factory(5)->create(['model_id' => $product->id]);
        }
    }
}
