<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class ProductsTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_products(): void
    {
        Product::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/products');

        $response->assertOk()
            ->assertJsonCount(3, 'data');
    }

    public function test_products_are_paginated(): void
    {
        Product::factory()->count(5)->create();

        $response = $this->getJson('/api/v1/products?page_size=2');

        $response->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('total', 5);
    }

    public function test_can_filter_products_by_name(): void
    {
        Product::factory()->create(['name' => 'Heavy Dumbbell']);
        Product::factory()->create(['name' => 'Light Treadmill']);

        $response = $this->getJson('/api/v1/products?name=Dumbbell');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Heavy Dumbbell');
    }

    public function test_can_filter_products_by_category(): void
    {
        $category = Category::factory()->create();
        Product::factory()->count(2)->create(['category_id' => $category->id]);
        Product::factory()->create();

        $response = $this->getJson("/api/v1/products?category_id={$category->id}");

        $response->assertOk()
            ->assertJsonCount(2, 'data');
    }

    public function test_can_filter_products_by_ids(): void
    {
        $products = Product::factory()->count(3)->create();
        $ids = $products->take(2)->pluck('id')->implode(',');

        $response = $this->getJson("/api/v1/products?ids={$ids}");

        $response->assertOk()
            ->assertJsonCount(2, 'data');
    }

    public function test_can_show_single_product(): void
    {
        $product = Product::factory()->create();

        $response = $this->getJson("/api/v1/products/{$product->id}");

        $response->assertOk()
            ->assertJsonPath('product.id', $product->id)
            ->assertJsonPath('product.name', $product->name);
    }

    public function test_show_returns_404_for_nonexistent_product(): void
    {
        $response = $this->getJson('/api/v1/products/99999');

        $response->assertNotFound();
    }
}
