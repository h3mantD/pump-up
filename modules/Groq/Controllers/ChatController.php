<?php

declare(strict_types=1);

namespace Modules\Groq\Controllers;

use App\Ai\Agents\ProductAssistant;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class ChatController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'message' => ['required', 'string'],
            'history' => ['sometimes', 'array'],
            'history.*.role' => ['required_with:history', 'string', 'in:user,assistant'],
            'history.*.content' => ['required_with:history', 'string'],
        ]);

        $agent = new ProductAssistant();

        /** @var array<array{role: string, content: string}> $history */
        $history = $request->input('history', []);

        if (! empty($history)) {
            $agent->withHistory($history);
        }

        $response = $agent->prompt($request->string('message')->toString());

        return response()->json([
            'role' => 'assistant',
            'content' => $response->text,
        ]);
    }
}
