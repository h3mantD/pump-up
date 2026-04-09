<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
final class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'review' => fake()->paragraph(),
            'rating' => fake()->numberBetween(1, 5),
            'model_type' => Product::class,
            'model_id' => Product::factory(),
            'author_type' => User::class,
            'author_id' => User::factory(),
        ];
    }
}
