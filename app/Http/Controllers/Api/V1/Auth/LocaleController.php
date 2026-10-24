<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\UpdateLocaleRequest;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;

class LocaleController extends Controller
{
    public function update(UpdateLocaleRequest $request): JsonResponse
    {
        $user = $request->user();
        $locale = $request->validated('locale');

        $user->forceFill([
            'locale' => $locale,
        ])->save();

        app()->setLocale($locale);

        return ApiResponse::success(
            data: [
                'locale' => $user->locale,
            ],
            message: 'Language preference updated successfully.',
            code: 'LOCALE_UPDATED',
        )->header('Content-Language', $locale);
    }
}
