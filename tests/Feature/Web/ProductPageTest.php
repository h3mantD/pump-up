<?php

declare(strict_types=1);

namespace Tests\Feature\Web;

use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class ProductPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_products_index_is_publicly_accessible(): void
    {
        Product::factory()->count(3)->create();

        $response = $this->get('/products');

        $response->assertOk();
        $response->assertInertia(
            fn ($page) => $page
                ->component('Products/Index')
                ->has('products.data', 3)
                ->has('categories')
        );
    }

    public function test_products_index_filters_by_category(): void
    {
        $category = Category::factory()->create();
        Product::factory()->count(2)->create(['category_id' => $category->id]);
        Product::factory()->create();

        $response = $this->get("/products?category_id={$category->id}");

        $response->assertOk();
        $response->assertInertia(
            fn ($page) => $page
                ->component('Products/Index')
                ->has('products.data', 2)
        );
    }

    public function test_products_index_filters_by_name(): void
    {
        Product::factory()->create(['name' => 'Heavy Dumbbell']);
        Product::factory()->create(['name' => 'Light Treadmill']);

        $response = $this->get('/products?name=Dumbbell');

        $response->assertOk();
        $response->assertInertia(
            fn ($page) => $page
                ->component('Products/Index')
                ->has('products.data', 1)
        );
    }

    public function test_product_show_is_publicly_accessible(): void
    {
        $product = Product::factory()->create();

        $response = $this->get("/products/{$product->id}");

        $response->assertOk();
        $response->assertInertia(
            fn ($page) => $page
                ->component('Products/Show')
                ->has('product')
                ->has('reviews')
                ->has('relatedProducts')
        );
    }

    public function test_product_show_includes_reviews(): void
    {
        $product = Product::factory()->create();
        $user = User::factory()->create();

        Review::factory()->count(3)->create([
            'model_type' => Product::class,
            'model_id' => $product->id,
            'author_type' => User::class,
            'author_id' => $user->id,
        ]);

        $response = $this->get("/products/{$product->id}");

        $response->assertOk();
        $response->assertInertia(
            fn ($page) => $page
                ->component('Products/Show')
                ->has('reviews.data', 3)
        );
    }

    public function test_product_show_returns_404_for_nonexistent(): void
    {
        $response = $this->get('/products/99999');

        $response->assertNotFound();
    }
}
