<?php

declare(strict_types=1);

namespace Modules\Groq\Enums;

enum AiModel: string
{
    case LLAMA2_70B = 'llama2-70b-4096';
    case MIXTRAL_8X7B = 'mixtral-8x7b-32768';
}
