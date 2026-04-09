<?php

declare(strict_types=1);

namespace Modules\Groq\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Modules\Groq\DTO\ChatCompletionPayload;
use Modules\Groq\Enums\Role;
use Modules\Groq\Services\ChatCompletion;
use Throwable;

final class ChatController extends Controller
{
    public function __invoke(Request $request, ChatCompletion $chatCompletion): JsonResponse
    {
        try {
            $validated = ChatCompletionPayload::validateAndCreate($request->all());

            $response = $chatCompletion->complete($validated, $request->string('chat_role', 'chatbot')->toString());

            /** @var array<array<string, mixed>> $choices */
            $choices = $response['choices'] ?? [];

            /** @var array<string, mixed>|null $firstChoice */
            $firstChoice = Arr::first($choices);

            return response()->json($firstChoice['message'] ?? [
                'role' => Role::ASSISTANT,
                'content' => 'I am sorry, I could not understand that. Could you please rephrase?',
            ]);
        } catch (Throwable $th) {
            if ($th instanceof \Illuminate\Validation\ValidationException) {
                return response()->json(['error' => $th->errors()], 412);
            }

            return response()->json(
                ['error' => $th->getMessage()],
                (int) ($th->getCode() ?: 500)
            );
        }
    }
}
