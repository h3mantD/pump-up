<?php

declare(strict_types=1);

namespace Tests\Feature\Modules;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

final class ElevenLabsTtsTest extends TestCase
{
    use RefreshDatabase;

    public function test_tts_returns_success(): void
    {
        Http::fake([
            'api.elevenlabs.io/*' => Http::response('fake-audio-content', 200),
        ]);

        $response = $this->postJson('/api/v1/eleven-labs/text-to-speech', [
            'text' => 'Hello, welcome to Pump Up!',
        ]);

        $response->assertOk()
            ->assertJson(['status' => true]);
    }

    public function test_tts_validates_required_fields(): void
    {
        $response = $this->postJson('/api/v1/eleven-labs/text-to-speech', []);

        $response->assertStatus(412)
            ->assertJsonStructure(['error']);
    }
}
