<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProfileResource;
use App\Models\Profile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function showPublic()
    {
        $profile = Profile::first();

        if (! $profile) {
            return response()->json([
                'message' => 'Profil belum tersedia.',
            ], 404);
        }

        return new ProfileResource($profile);
    }

    public function show(Request $request)
    {
        $profile = Profile::where('user_id', $request->user()->id)->firstOrFail();

        return new ProfileResource($profile);
    }

    public function update(Request $request)
    {
        $profile = Profile::where('user_id', $request->user()->id)->firstOrFail();

        $validated = $request->validate([
            'full_name' => 'sometimes|string|max:255',
            'headline' => 'sometimes|string|max:255',
            'bio' => 'sometimes|string',
            'avatar' => 'sometimes|string|max:255',
            'resume_url' => 'sometimes|url|max:255',
            'location' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:50',
            'email' => 'sometimes|email|max:255',
            'website' => 'sometimes|url|max:255',
            'timezone' => 'sometimes|string|max:100',
            'social_links' => 'sometimes|array',
        ]);

        $profile->update($validated);

        return new ProfileResource($profile->fresh());
    }
}