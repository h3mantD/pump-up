<?php

declare(strict_types=1);

namespace Modules\ElevenLabs\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Ai\Audio;
use Laravel\Ai\Enums\Lab;

final class TextToSpeechController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'text' => ['required', 'string'],
        ]);

        $audio = Audio::of($request->string('text')->value())
            ->generate(Lab::ElevenLabs);

        $path = $audio->storeAs('audio/' . Str::uuid() . '.mp3', 'public');

        return response()->json(['status' => true, 'path' => $path]);
    }
}
