<?php

declare(strict_types=1);

namespace Tests\Feature\Modules;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Ai\Audio;
use Tests\TestCase;

final class ElevenLabsTtsTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_tts_returns_success(): void
    {
        Audio::fake();

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/v1/eleven-labs/text-to-speech', [
                'text' => 'Hello, welcome to Pump Up!',
            ]);

        $response->assertOk()
            ->assertJsonStructure(['status', 'path']);

        Audio::assertGenerated(fn ($prompt) => $prompt->contains('Hello'));
    }

    public function test_tts_validates_required_fields(): void
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/v1/eleven-labs/text-to-speech', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['text']);
    }
}
