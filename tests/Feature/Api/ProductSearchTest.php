<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class ProductSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_search_requires_query_parameter(): void
    {
        $response = $this->getJson('/api/v1/products/search');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['q']);
    }

    public function test_search_validates_limit_parameter(): void
    {
        $response = $this->getJson('/api/v1/products/search?q=dumbbell&limit=100');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['limit']);
    }

    public function test_search_is_publicly_accessible(): void
    {
        // Verify the endpoint exists and is public (no auth required)
        // Actual vector search only works on PostgreSQL with pgvector
        // On SQLite it will error, so we just verify it doesn't return 401 or 404
        $response = $this->getJson('/api/v1/products/search?q=test');

        $this->assertNotEquals(401, $response->status());
        $this->assertNotEquals(404, $response->status());
    }
}
