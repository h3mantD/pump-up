<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class ProductBulkOperationsTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_can_bulk_delete_products(): void
    {
        $products = Product::factory()->count(3)->create();
        $ids = $products->pluck('id')->toArray();

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/v1/products/bulk-delete', ['ids' => $ids]);

        $response->assertOk()
            ->assertJsonPath('deleted', 3);

        $this->assertDatabaseCount('products', 0);
    }

    public function test_bulk_delete_validates_ids(): void
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/v1/products/bulk-delete', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['ids']);
    }

    public function test_can_bulk_update_stock(): void
    {
        $product1 = Product::factory()->create(['stock' => 10]);
        $product2 = Product::factory()->create(['stock' => 20]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->patchJson('/api/v1/products/bulk-stock', [
                'products' => [
                    ['id' => $product1->id, 'stock' => 50],
                    ['id' => $product2->id, 'stock' => 0],
                ],
            ]);

        $response->assertOk()
            ->assertJsonPath('updated', 2);

        $this->assertDatabaseHas('products', ['id' => $product1->id, 'stock' => 50]);
        $this->assertDatabaseHas('products', ['id' => $product2->id, 'stock' => 0]);
    }

    public function test_bulk_stock_validates_products(): void
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->patchJson('/api/v1/products/bulk-stock', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['products']);
    }

    public function test_can_bulk_update_status(): void
    {
        $product1 = Product::factory()->create(['status' => 'available']);
        $product2 = Product::factory()->create(['status' => 'available']);

        $response = $this->actingAs($this->user, 'sanctum')
            ->patchJson('/api/v1/products/bulk-status', [
                'products' => [
                    ['id' => $product1->id, 'status' => 'unavailable'],
                    ['id' => $product2->id, 'status' => 'out_of_stock'],
                ],
            ]);

        $response->assertOk()
            ->assertJsonPath('updated', 2);

        $this->assertDatabaseHas('products', ['id' => $product1->id, 'status' => 'unavailable']);
        $this->assertDatabaseHas('products', ['id' => $product2->id, 'status' => 'out_of_stock']);
    }

    public function test_bulk_status_validates_products(): void
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->patchJson('/api/v1/products/bulk-status', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['products']);
    }
}
