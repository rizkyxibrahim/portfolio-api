<?php

namespace App\Support;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;

class ApiResponse
{
    public static function success(
        mixed $data = null,
        string $message = 'Success.',
        string $code = 'SUCCESS',
        int $status = 200,
        array $meta = []
    ): JsonResponse {
        if ($data instanceof JsonResource) {
            $data = $data->resolve(request());
        } elseif ($data instanceof LengthAwarePaginator) {
            $data = [
                'data' => $data->items(),
                'pagination' => [
                    'current_page' => $data->currentPage(),
                    'per_page' => $data->perPage(),
                    'total' => $data->total(),
                    'last_page' => $data->lastPage(),
                ],
            ];
        }

        return response()->json(array_merge([
            'status' => 'success',
            'code' => $code,
            'message' => $message,
            'data' => $data,
            'meta' => array_merge([
                'request_id' => request()->id ?? null,
                'pagination' => null,
            ], $meta),
        ], []), $status);
    }

    public static function error(
        string $title = 'Error.',
        string $detail = '',
        string $code = 'ERROR',
        int $status = 500,
        array $errors = []
    ): JsonResponse {
        return response()->json([
            'type' => 'about:blank',
            'title' => $title,
            'status' => $status,
            'detail' => $detail,
            'instance' => request()->path(),
            'code' => $code,
            'errors' => $errors,
            'request_id' => request()->id ?? null,
        ], $status);
    }

    public static function validationError(array $errors): JsonResponse
    {
        return self::error(
            title: 'Validation failed.',
            detail: 'One or more fields contain invalid values.',
            code: 'VALIDATION_ERROR',
            status: 422,
            errors: $errors
        );
    }

    public static function unauthenticated(): JsonResponse
    {
        return self::error(
            title: 'Unauthorized',
            detail: 'Authentication is required to access this resource.',
            code: 'AUTHENTICATION_REQUIRED',
            status: 401
        );
    }

    public static function notFound(string $detail = 'The requested resource was not found.'): JsonResponse
    {
        return self::error(
            title: 'Not Found',
            detail: $detail,
            code: 'RESOURCE_NOT_FOUND',
            status: 404
        );
    }

    public static function forbidden(string $detail = 'You do not have permission to access this resource.'): JsonResponse
    {
        return self::error(
            title: 'Forbidden',
            detail: $detail,
            code: 'ACCESS_FORBIDDEN',
            status: 403
        );
    }
}
