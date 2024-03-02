<?php

declare(strict_types=1);

namespace Modules\ElevenLabs\DTO;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
#[MapOutputName(SnakeCaseMapper::class)]
final class TextToSpeechPayload extends Data
{
    public function __construct(
        public readonly string $text,
        public ?VoiceSettings $voiceSettings,
        public ?string $modelId,
        public readonly ?array $pronunciationDictionaryLocators = [],
    ) {
        if (! $this->modelId) {
            $this->modelId = config('elevenlabs.model_id');
        }

        if (! $this->voiceSettings) {
            $this->voiceSettings = new VoiceSettings();
        }
    }
}
