<?php

namespace App\Http\Middleware;

use App\Helpers\ResponseHelper;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;

class JwtMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        try {
            JWTAuth::parseToken()->authenticate();
        } catch (TokenExpiredException $e) {
            return ResponseHelper::JsonWithError('Your token has expired. Please login again.', Response::HTTP_UNAUTHORIZED);

        } catch (TokenInvalidException $e) {
            return ResponseHelper::JsonWithError('Invalid authentication token.', Response::HTTP_UNAUTHORIZED);

        } catch (JWTException $e) {
            return ResponseHelper::JsonWithError('Authentication token not found.', Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
