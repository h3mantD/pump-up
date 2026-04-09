<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_belongs_to_category(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);

        $this->assertTrue($product->category->is($category));
    }

    public function test_product_has_category_name_accessor(): void
    {
        $category = Category::factory()->create(['name' => 'Cardio Machines']);
        $product = Product::factory()->create(['category_id' => $category->id]);

        $this->assertEquals('Cardio Machines', $product->category_name);
    }
}
