<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        if (auth()->user()) {
            return $this->refresh();
        }

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => Response::HTTP_FORBIDDEN], Response::HTTP_FORBIDDEN);
        }

        $user = User::all()->where('email', '=', $credentials['email'])->first();
        auth('api')->login($user);
        return $this->respondWithToken($token);
    }

    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        if ($token = auth('api')->refresh(true, true))
            return $this->respondWithToken($token);
        return response()->json(['error' => Response::HTTP_FORBIDDEN], Response::HTTP_FORBIDDEN);
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return JsonResponse
     */
    protected function respondWithToken(string $token): JsonResponse
    {
        return response()->json([
            'token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ], Response::HTTP_OK);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth('api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Get the authenticated User.
     *
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => auth()->user(),
            'code' => Response::HTTP_OK
        ], Response::HTTP_OK);
    }
}
