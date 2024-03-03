<?php

declare(strict_types=1);

namespace Modules\Groq\DTO;

use Modules\Groq\Enums\Role;
use Spatie\LaravelData\Data;

final class MessagePayload extends Data
{
    public function __construct(public readonly Role $role, public readonly string $content)
    {
    }
}
