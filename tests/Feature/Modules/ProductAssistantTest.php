<?php

declare(strict_types=1);

namespace Tests\Feature\Modules;

use App\Ai\Agents\ProductAssistant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class ProductAssistantTest extends TestCase
{
    use RefreshDatabase;

    public function test_agent_returns_response(): void
    {
        ProductAssistant::fake(['I recommend the Heavy Dumbbell for strength training.']);

        $response = $this->postJson('/api/v1/groq/chat', [
            'message' => 'What equipment should I use for arms?',
        ]);

        $response->assertOk()
            ->assertJsonStructure(['role', 'content']);

        ProductAssistant::assertPrompted(fn ($prompt): bool => str_contains((string) $prompt->prompt, 'arms'));
    }

    public function test_chat_validates_message(): void
    {
        $response = $this->postJson('/api/v1/groq/chat', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['message']);
    }
}
