<?php

declare(strict_types=1);

namespace Modules\Groq\Enums;

enum Role: string
{
    case USER = 'user';
    case ASSISTANT = 'assistant';
}
