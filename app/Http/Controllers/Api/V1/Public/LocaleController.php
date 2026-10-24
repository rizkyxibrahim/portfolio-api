<?php

namespace App\Http\Controllers\Api\V1\Public;

use App\Http\Controllers\Controller;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;

class LocaleController extends Controller
{
    public function __invoke(): JsonResponse
    {
        return ApiResponse::success(
            data: [
                [
                    'code' => 'en',
                    'name' => 'English',
                    'native_name' => 'English',
                    'is_default' => true,
                ],
                [
                    'code' => 'id',
                    'name' => 'Indonesian',
                    'native_name' => 'Bahasa Indonesia',
                    'is_default' => false,
                ],
            ],
            message: 'Supported languages retrieved successfully.',
            code: 'LOCALES_RETRIEVED',
        )->header('Content-Language', 'en');
    }
}
