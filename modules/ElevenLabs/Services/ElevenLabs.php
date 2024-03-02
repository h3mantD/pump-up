<?php

declare(strict_types=1);

namespace Modules\ElevenLabs\Services;

use App\Enums\Method;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;

final class ElevenLabs
{
    public function __construct(public PendingRequest $pendingRequest)
    {
    }

    public function send(Method $method, string $url, ?array $body = null): Response
    {
        return $this->pendingRequest->send(method: $method->value, url: $url, options: [
            'json' => $body,
        ]);
    }
}
