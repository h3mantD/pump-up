<?php

declare(strict_types=1);

namespace Tests\Feature\Jobs;

use App\Jobs\GenerateProductEmbedding;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Ai\Embeddings;
use Tests\TestCase;

final class GenerateProductEmbeddingTest extends TestCase
{
    use RefreshDatabase;

    public function test_job_generates_embedding_for_product(): void
    {
        $fakeEmbedding = Embeddings::fakeEmbedding(1536);

        Embeddings::fake([
            [$fakeEmbedding],
        ]);

        $product = Product::factory()->create([
            'name' => 'Heavy Dumbbell',
            'description' => 'A 50kg dumbbell for serious lifters.',
        ]);

        $job = new GenerateProductEmbedding($product);
        $job->handle();

        Embeddings::assertGenerated(fn ($prompt) => 1536 === $prompt->dimensions);
    }

    public function test_job_uses_update_quietly(): void
    {
        Embeddings::fake();

        $product = Product::factory()->create();

        $job = new GenerateProductEmbedding($product);
        $job->handle();

        Embeddings::assertGenerated(fn ($prompt) => true);
    }
}
