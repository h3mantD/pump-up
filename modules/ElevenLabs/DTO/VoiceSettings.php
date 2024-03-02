<?php

declare(strict_types=1);

namespace Modules\ElevenLabs\DTO;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
#[MapOutputName(SnakeCaseMapper::class)]
final class VoiceSettings extends Data
{
    public function __construct(
        public readonly int $similarityBoost = 1,
        public readonly int $stability = 1,
        public readonly int $style = 1,
        public readonly ?bool $useSpeakerBoost = false,
    ) {
    }
}
