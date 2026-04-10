<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class CategoryResourceTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['is_admin' => true]);
    }

    public function test_admin_category_list_is_accessible(): void
    {
        $response = $this->actingAs($this->admin)
            ->get('/admin/categories');

        $response->assertOk();
    }

    public function test_admin_category_create_is_accessible(): void
    {
        $response = $this->actingAs($this->admin)
            ->get('/admin/categories/create');

        $response->assertOk();
    }

    public function test_admin_category_edit_is_accessible(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->admin)
            ->get("/admin/categories/{$category->id}/edit");

        $response->assertOk();
    }
}
