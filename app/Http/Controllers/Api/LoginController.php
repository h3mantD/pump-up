<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

final class LoginController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if (! auth()->attempt($request->only('email', 'password'))) {
                abort(401);
            }

            /**
             * @var \App\Models\User $user
             */
            $user = auth()->user();

            $token = $user->createToken('auth-name')->plainTextToken;

            return response()->json([
                'status' => 'success',
                'token' => $token,
            ]);
        } catch (\Illuminate\Validation\ValidationException $th) {
            return response()->json(['error' => $th->errors()], 412);
        } catch (HttpException $th) {
            return response()->json(['error' => $th->getMessage()], $th->getStatusCode());
        } catch (Throwable $th) {
            return response()->json(
                ['error' => $th->getMessage()],
                $th->getCode() ?: 500
            );
        }
    }

    public function logout(Request $request): JsonResponse
    {
        /**
         * @var \App\Models\User $user
         */
        $user = $request->user();

        $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();

        return response()->json([
            'status' => 'success',
        ]);
    }
}
