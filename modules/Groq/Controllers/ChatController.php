<?php

declare(strict_types=1);

namespace Modules\Groq\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Groq\DTO\ChatCompletionPayload;
use Modules\Groq\Services\ChatCompletion;
use Throwable;

final class ChatController extends Controller
{
    public function __invoke(Request $request, ChatCompletion $chatCompletion): JsonResponse
    {
        try {
            $validated = ChatCompletionPayload::validateAndCreate($request->all());

            return response()->json($chatCompletion->complete($validated, $request->get('chat_role', 'chatbot')));
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
