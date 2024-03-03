<?php

declare(strict_types=1);

namespace Modules\Groq\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Modules\Groq\DTO\ChatCompletionPayload;
use Modules\Groq\Services\ChatCompletion;
use Throwable;

final class ChatController extends Controller
{
    public function __invoke(Request $request, ChatCompletion $chatCompletion): JsonResponse
    {
        try {
            $validated = ChatCompletionPayload::validateAndCreate($request->all());

            $response = $chatCompletion->complete($validated, $request->get('chat_role', 'chatbot'));

            return response()->json(Arr::first($response['choices'])['message'] ?? []);
        } catch (Throwable $th) {
            if ($th instanceof \Illuminate\Validation\ValidationException) {
                return response()->json(['error' => $th->errors()], 412);
            }

            return response()->json(
                ['error' => $th->getMessage()],
                $th->getCode() ? $th->getCode() : 500
            );
        }
    }
}
