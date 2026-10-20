<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

abstract class Controller
{
    protected function serverError(
        Throwable $exception,
        string $message = 'An unexpected error occurred.'
    ): JsonResponse {
        report($exception);

        return ResponseHelper::JsonWithError(
            $message,
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }
}
