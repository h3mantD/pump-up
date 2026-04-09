<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Ai\Embeddings;
use Tests\TestCase;

final class ProductSimilarityTest extends TestCase
{
    use RefreshDatabase;

    public function test_embedding_is_cast_to_array(): void
    {
        $product = Product::factory()->create();

        $product->updateQuietly(['embedding' => [0.1, 0.2, 0.3]]);
        $product->refresh();

        $this->assertIsArray($product->embedding);
        $this->assertEquals([0.1, 0.2, 0.3], $product->embedding);
    }

    #[\PHPUnit\Framework\Attributes\Group('pgsql')]
    public function test_similar_to_scope_queries_with_vector_similarity(): void
    {
        if ('sqlite' === config('database.default')) {
            $this->markTestSkipped('pgvector requires PostgreSQL');
        }

        Embeddings::fake();

        $product = Product::factory()->create();
        $product->updateQuietly(['embedding' => Embeddings::fakeEmbedding(1536)]);

        $results = Product::similarTo('gym equipment')->get();

        $this->assertCount(1, $results);
        $this->assertTrue($results->first()->is($product));
    }
}
