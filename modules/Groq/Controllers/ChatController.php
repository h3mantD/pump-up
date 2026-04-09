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
        ]);

        $response = new ProductAssistant()->prompt($request->string('message')->toString());

        return response()->json([
            'role' => 'assistant',
            'content' => $response->text,
        ]);
    }
}
