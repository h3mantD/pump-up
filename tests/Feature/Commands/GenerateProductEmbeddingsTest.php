<?php

declare(strict_types=1);

namespace Tests\Feature\Commands;

use App\Jobs\GenerateProductEmbedding;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

final class GenerateProductEmbeddingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_command_dispatches_jobs_for_products_without_embeddings(): void
    {
        Queue::fake();

        Product::factory()->count(3)->create();

        $this->artisan('products:generate-embeddings')
            ->assertSuccessful();

        Queue::assertCount(3);
        Queue::assertPushed(GenerateProductEmbedding::class, 3);
    }

    public function test_command_with_force_dispatches_for_all_products(): void
    {
        Queue::fake();

        $product = Product::factory()->create();
        $product->updateQuietly(['embedding' => [0.1, 0.2]]);

        Product::factory()->count(2)->create();

        $this->artisan('products:generate-embeddings --force')
            ->assertSuccessful();

        Queue::assertPushed(GenerateProductEmbedding::class, 3);
    }

    public function test_command_skips_products_with_existing_embeddings(): void
    {
        Queue::fake();

        $product = Product::factory()->create();
        $product->updateQuietly(['embedding' => [0.1, 0.2]]);

        Product::factory()->create();

        $this->artisan('products:generate-embeddings')
            ->assertSuccessful();

        Queue::assertPushed(GenerateProductEmbedding::class, 1);
    }
}
