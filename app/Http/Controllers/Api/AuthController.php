<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => $validated['password'],
            ]);

            $token = JWTAuth::fromUser($user);

            return ResponseHelper::JsonWithSuccess(
                new UserResource($user, $token),
                'Registered successfully.',
                Response::HTTP_CREATED
            );
        } catch (\Throwable $exception) {
            return $this->serverError($exception, 'Registration failed.');
        }
    }

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $credentials = $request->validated();

            if (! $token = JWTAuth::attempt($credentials)) {
                return ResponseHelper::JsonWithError(
                    'Invalid email or password.',
                    Response::HTTP_UNAUTHORIZED
                );
            }

            $user = JWTAuth::user();

            return ResponseHelper::JsonWithSuccess(
                new UserResource($user, $token),
                'Logged in successfully.',
                Response::HTTP_OK
            );
        } catch (\Throwable $exception) {
            return $this->serverError($exception, 'Login failed.');
        }
    }

    public function logout(): JsonResponse
    {
        try {
            JWTAuth::parseToken()->invalidate();

            return ResponseHelper::JsonWithSuccess(
                null,
                'Successfully logged out.',
                Response::HTTP_OK
            );
        } catch (\Throwable $exception) {
            return $this->serverError($exception, 'Logout failed.');
        }
    }
}
