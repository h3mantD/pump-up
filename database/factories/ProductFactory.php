<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
final class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'description' => fake()->paragraph(),
            'price' => fake()->randomFloat(2, 5, 5000),
            'stock' => fake()->numberBetween(1, 500),
            'status' => 'available',
            'category_id' => Category::factory(),
        ];
    }

    public function unavailable(): static
    {
        return $this->state(['status' => 'unavailable']);
    }

    public function outOfStock(): static
    {
        return $this->state(['status' => 'out_of_stock']);
    }
}
