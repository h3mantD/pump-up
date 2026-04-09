<?php

declare(strict_types=1);

namespace Tests\Feature\Modules;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

final class GroqChatTest extends TestCase
{
    use RefreshDatabase;

    public function test_chat_returns_response_in_chatbot_mode(): void
    {
        Http::fake([
            'api.groq.com/*' => Http::response([
                'choices' => [
                    [
                        'message' => [
                            'role' => 'assistant',
                            'content' => 'I recommend starting with cardio exercises.',
                        ],
                    ],
                ],
            ]),
        ]);

        $response = $this->postJson('/api/v1/groq/chat', [
            'messages' => [
                ['role' => 'user', 'content' => 'What exercises should I do?'],
            ],
            'temperature' => 0.7,
            'chat_role' => 'chatbot',
        ]);

        $response->assertOk()
            ->assertJsonPath('role', 'assistant')
            ->assertJsonPath('content', 'I recommend starting with cardio exercises.');
    }

    public function test_chat_validates_required_fields(): void
    {
        $response = $this->postJson('/api/v1/groq/chat', []);

        $response->assertStatus(412)
            ->assertJsonStructure(['error']);
    }
}
