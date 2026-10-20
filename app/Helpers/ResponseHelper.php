<?php

namespace App\Helpers;

use Illuminate\Pagination\LengthAwarePaginator;

class ResponseHelper
{
    // Success response
    public static function JsonWithSuccess($data = null, string $message = 'Success', int $status = 200)
    {
        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => __($message),
        ], $status);
    }

    // Error response
    public static function JsonWithError(string $message = 'Error', int $status = 400, array $details = [])
    {
        $error = ['message' => __($message)];
        if (!empty($details)) {
            $error['details'] = $details;
        }

        return response()->json([
            'success' => false,
            'errors' => $error,
        ], $status);
    }

    // Paginated response
    public static function JsonWithPagination(LengthAwarePaginator $paginator, $data = null, string $message = 'Success')
    {
        return response()->json([
            'success' => true,
            'data' => $data ?? $paginator->items(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'number_of_items' => $paginator->count(),
                'number_of_pages' => $paginator->lastPage(),
            ],
            'message' => __($message),
        ]);
    }
}
