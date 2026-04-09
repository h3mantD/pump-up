<?php

declare(strict_types=1);

namespace Modules\ElevenLabs\Services;

use App\Enums\Method;
use Modules\ElevenLabs\DTO\TextToSpeechPayload;

final class TextToSpeech
{
    public function __construct(public ElevenLabs $elevenLabs) {}

    /**
     * @return array{status: bool}
     */
    public function handle(TextToSpeechPayload $textToSpeechPayload): array
    {
        $response = $this->elevenLabs->send(
            method: Method::POST,
            url: 'text-to-speech/' . (is_string($voiceId = config('elevenlabs.voice_id')) ? $voiceId : ''),
            body: $textToSpeechPayload->all()
        );

        if ($response->successful()) {
            file_put_contents(storage_path('app/public/audio.mp3'), $response->body());

            return ['status' => true];
        }

        return ['status' => false];
    }
}
