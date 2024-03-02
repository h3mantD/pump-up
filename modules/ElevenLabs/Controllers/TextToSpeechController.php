<?php

declare(strict_types=1);

namespace Modules\ElevenLabs\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\ElevenLabs\DTO\TextToSpeechPayload;
use Modules\ElevenLabs\Services\TextToSpeech;
use Throwable;

final class TextToSpeechController extends Controller
{
    public function __invoke(Request $request, TextToSpeech $textToSpeech): JsonResponse
    {
        try {
            $validator = TextToSpeechPayload::validateAndCreate($request->all());

            $response = $textToSpeech->handle($validator);

            return response()->json($response);
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
