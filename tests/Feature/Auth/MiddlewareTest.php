<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class MiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_endpoints_accessible_without_auth(): void
    {
        Product::factory()->count(2)->create();
        $product = Product::first();
        $category = Category::first();

        $this->getJson('/api/v1/products')->assertOk();
        $this->getJson("/api/v1/products/{$product->id}")->assertOk();
        $this->getJson("/api/v1/products/{$product->id}/reviews")->assertOk();
        $this->getJson('/api/v1/categories')->assertOk();
        $this->getJson("/api/v1/categories/{$category->id}")->assertOk();
    }

    public function test_product_write_endpoints_require_auth(): void
    {
        $product = Product::factory()->create();

        $this->postJson('/api/v1/products', [])->assertUnauthorized();
        $this->putJson("/api/v1/products/{$product->id}", [])->assertUnauthorized();
        $this->deleteJson("/api/v1/products/{$product->id}")->assertUnauthorized();
        $this->postJson('/api/v1/products/bulk-delete', [])->assertUnauthorized();
        $this->patchJson('/api/v1/products/bulk-stock', [])->assertUnauthorized();
        $this->patchJson('/api/v1/products/bulk-status', [])->assertUnauthorized();
    }

    public function test_chat_endpoint_is_publicly_accessible(): void
    {
        $this->postJson('/api/v1/groq/chat', [])->assertStatus(422);
    }

    public function test_tts_endpoint_is_publicly_accessible(): void
    {
        $this->postJson('/api/v1/eleven-labs/text-to-speech', [])->assertStatus(422);
    }

    public function test_protected_endpoints_work_with_valid_token(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/products', [
                'name' => 'Test Product',
                'description' => 'A test.',
                'price' => 10.00,
                'stock' => 5,
                'status' => 'available',
                'category_id' => $category->id,
            ]);

        $response->assertStatus(201);
    }
}
