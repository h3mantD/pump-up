<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

final class ProductCrudTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_can_create_product(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/v1/products', [
                'name' => 'Test Dumbbell',
                'description' => 'A heavy dumbbell for testing.',
                'price' => 99.99,
                'stock' => 50,
                'status' => 'available',
                'category_id' => $category->id,
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('product.name', 'Test Dumbbell');

        $this->assertDatabaseHas('products', ['name' => 'Test Dumbbell']);
    }

    public function test_create_product_with_image_upload(): void
    {
        Storage::fake('public');
        $category = Category::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/v1/products', [
                'name' => 'Dumbbell with Image',
                'description' => 'Has an image.',
                'price' => 50.00,
                'stock' => 10,
                'status' => 'available',
                'category_id' => $category->id,
                'image' => UploadedFile::fake()->image('dumbbell.jpg'),
            ]);

        $response->assertStatus(201);

        $product = Product::where('name', 'Dumbbell with Image')->first();
        $this->assertNotNull($product->image);
        Storage::disk('public')->assertExists('products/' . basename($product->image));
    }

    public function test_create_product_with_image_url(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/v1/products', [
                'name' => 'Dumbbell with URL',
                'description' => 'Has a URL image.',
                'price' => 50.00,
                'stock' => 10,
                'status' => 'available',
                'category_id' => $category->id,
                'image_url' => 'https://example.com/dumbbell.jpg',
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('product.image', 'https://example.com/dumbbell.jpg');
    }

    public function test_create_product_fails_with_validation_errors(): void
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/v1/products', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'description', 'price', 'stock', 'status', 'category_id']);
    }

    public function test_can_update_product(): void
    {
        $product = Product::factory()->create(['name' => 'Old Name']);

        $response = $this->actingAs($this->user, 'sanctum')
            ->putJson("/api/v1/products/{$product->id}", [
                'name' => 'New Name',
            ]);

        $response->assertOk()
            ->assertJsonPath('product.name', 'New Name');

        $this->assertDatabaseHas('products', ['id' => $product->id, 'name' => 'New Name']);
    }

    public function test_can_delete_product(): void
    {
        $product = Product::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')
            ->deleteJson("/api/v1/products/{$product->id}");

        $response->assertOk()
            ->assertJson(['message' => 'Product deleted']);

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_delete_returns_404_for_nonexistent_product(): void
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->deleteJson('/api/v1/products/99999');

        $response->assertNotFound();
    }
}
