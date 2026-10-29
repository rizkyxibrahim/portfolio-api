<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Public profile (no authentication required)
     */
    public function showPublic()
    {
        $profile = Profile::first();

        return ApiResponse::success(
            data: $profile,
            message: 'Public profile retrieved.',
            code: 'PUBLIC_PROFILE_RETRIEVED',
        );
    }

    /**
     * Public contact (no authentication required)
     */
    public function publicContact()
    {
        $profile = Profile::first();

        return ApiResponse::success(
            data: [
                'email' => $profile?->email,
                'phone' => $profile?->phone,
            ],
            message: 'Public contact endpoint.',
            code: 'PUBLIC_CONTACT_READY',
        );
    }

    /**
     * Profile of the authenticated user (Sanctum required)
     */
    public function show(Request $request)
    {
        $profile = Profile::where('user_id', $request->user()->id)->firstOrFail();

        return ApiResponse::success(
            data: $profile,
            message: 'Your profile.',
            code: 'PROFILE_RETRIEVED',
        );
    }

    /**
     * Update profile of the authenticated user (Sanctum required)
     */
    public function update(Request $request)
    {
        $profile = Profile::where('user_id', $request->user()->id)->firstOrFail();

        $validated = $request->validate([
            'full_name'     => 'sometimes|string|max:255',
            'headline'      => 'sometimes|string|max:255',
            'bio'           => 'sometimes|string',
            'avatar'        => 'sometimes|string|max:255',
            'resume_url'    => 'sometimes|url|max:255',
            'location'      => 'sometimes|string|max:255',
            'phone'         => 'sometimes|string|max:50',
            'email'         => 'sometimes|email|max:255',
            'website'       => 'sometimes|url|max:255',
            'timezone'      => 'sometimes|string|max:100',
            'social_links'  => 'sometimes|array',
        ]);

        $profile->update($validated);

        return ApiResponse::success(
            data: $profile->fresh(),
            message: 'Profile updated successfully.',
            code: 'PROFILE_UPDATED',
        );
    }
}
