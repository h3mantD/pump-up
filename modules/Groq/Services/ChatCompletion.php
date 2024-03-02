<?php

declare(strict_types=1);

namespace Modules\Groq\Services;

use App\Enums\Method;
use Illuminate\Support\Facades\Log;
use Modules\Groq\DTO\ChatCompletionPayload;

final class ChatCompletion
{
    public function __construct(public Groq $groq)
    {
    }

    public function complete(ChatCompletionPayload $chatCompletionPayload): array
    {
        $response = $this->groq->send(
            method: Method::POST,
            url: 'chat/completions',
            body: $chatCompletionPayload->toArray()
        );

        Log::info($response->body());

        return $response->json();
    }
}
