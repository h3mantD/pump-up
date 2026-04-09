<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class ProductReviewsTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_product_reviews(): void
    {
        $product = Product::factory()->create();
        $user = User::factory()->create();

        Review::factory()->count(3)->create([
            'model_type' => Product::class,
            'model_id' => $product->id,
            'author_type' => User::class,
            'author_id' => $user->id,
        ]);

        $response = $this->getJson("/api/v1/products/{$product->id}/reviews");

        $response->assertOk()
            ->assertJsonCount(3, 'data');
    }

    public function test_reviews_are_paginated(): void
    {
        $product = Product::factory()->create();
        $user = User::factory()->create();

        Review::factory()->count(8)->create([
            'model_type' => Product::class,
            'model_id' => $product->id,
            'author_type' => User::class,
            'author_id' => $user->id,
        ]);

        $response = $this->getJson("/api/v1/products/{$product->id}/reviews?page_size=3");

        $response->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonPath('total', 8);
    }

    public function test_reviews_returns_404_for_nonexistent_product(): void
    {
        $response = $this->getJson('/api/v1/products/99999/reviews');

        $response->assertNotFound();
    }
}
