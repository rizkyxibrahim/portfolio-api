<?php

namespace App\Http\Controllers\Api\V1\Public;

use App\Http\Controllers\Controller;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;

class HealthController extends Controller
{
    public function __invoke(): JsonResponse
    {
        return ApiResponse::success(
            data: [
                'status' => 'ok',
            ],
            message: 'API is running.',
            code: 'API_HEALTHY',
        );
    }
}
