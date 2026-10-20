<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{

    public function register(RegisterRequest $request)
    {
        try {
            $data = $request->validated();

            $user = User::create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => $data['password'],
            ]);

            $token = JWTAuth::fromUser($user);

            $data = $user;
            $data['access_token'] = $token;

            return ResponseHelper::JsonWithSuccess($data, 'registered successfully.', Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return ResponseHelper::JsonWithError($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->validated();

            if (!$token = JWTAuth::attempt($credentials)) {
                return ResponseHelper::JsonWithError('Invalid email or password.', Response::HTTP_UNAUTHORIZED);
            }

            $data = auth()->user();
            $data->access_token = $token;

            return ResponseHelper::JsonWithSuccess($data, 'logged in successfully.', Response::HTTP_OK);
        } catch (\Exception $e) {
            return ResponseHelper::JsonWithError($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function logout()
    {
        try
        {
            auth()->logout();
            return ResponseHelper::JsonWithSuccess(null,'Successfully logged out.',Response::HTTP_OK);
        }
        catch (\Exception $e) {
            return ResponseHelper::JsonWithError('Logout failed.',Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
