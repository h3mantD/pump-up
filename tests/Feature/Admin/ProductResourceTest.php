<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class ProductResourceTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['is_admin' => true]);
    }

    public function test_admin_product_list_is_accessible(): void
    {
        $response = $this->actingAs($this->admin)
            ->get('/admin/products');

        $response->assertOk();
    }

    public function test_admin_product_create_is_accessible(): void
    {
        $response = $this->actingAs($this->admin)
            ->get('/admin/products/create');

        $response->assertOk();
    }

    public function test_admin_product_edit_is_accessible(): void
    {
        $product = Product::factory()->create();

        $response = $this->actingAs($this->admin)
            ->get("/admin/products/{$product->id}/edit");

        $response->assertOk();
    }

    public function test_admin_requires_authentication(): void
    {
        $response = $this->get('/admin/products');

        $response->assertRedirect();
    }

    public function test_non_admin_cannot_access_admin(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $response = $this->actingAs($user)
            ->get('/admin/products');

        $response->assertForbidden();
    }
}
