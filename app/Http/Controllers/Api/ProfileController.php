<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Public profile (tanpa login)
     */
    public function showPublic()
    {
        $profile = Profile::first();

        if (! $profile) {
            return response()->json([
                'status'  => 'error',
                'code'    => 404,
                'message' => 'Profil belum tersedia.',
                'data'    => null,
            ], 404);
        }

        return response()->json([
            'status'  => 'success',
            'code'    => 200,
            'message' => 'Public profile retrieved.',
            'data'    => $profile,
        ]);
    }

    /**
     * Public contact (tanpa login)
     */
    public function publicContact()
    {
        $profile = Profile::first();

        return response()->json([
            'status'  => 'success',
            'code'    => 200,
            'message' => 'Public contact endpoint.',
            'data'    => [
                'email' => $profile?->email,
                'phone' => $profile?->phone,
            ],
        ]);
    }

    /**
     * Profil pengguna yang sedang login (memerlukan Sanctum)
     */
    public function show(Request $request)
    {
        $profile = Profile::where('user_id', $request->user()->id)->firstOrFail();

        return response()->json([
            'status'  => 'success',
            'code'    => 200,
            'message' => 'Profil Anda.',
            'data'    => $profile,
        ]);
    }

    /**
     * Perbarui profil pengguna yang sedang login (memerlukan Sanctum)
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

        return response()->json([
            'status'  => 'success',
            'code'    => 200,
            'message' => 'Profil berhasil diperbarui.',
            'data'    => $profile->fresh(),
        ]);
    }
}
