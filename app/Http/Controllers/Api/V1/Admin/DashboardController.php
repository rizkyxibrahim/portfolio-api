<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function __invoke(): JsonResponse
    {
        return ApiResponse::success(
            data: [
                'section' => 'admin',
            ],
            message: 'Admin dashboard retrieved successfully.',
            code: 'ADMIN_DASHBOARD_RETRIEVED',
        );
    }
}
