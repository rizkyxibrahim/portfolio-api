<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreSkillRequest;
use App\Http\Requests\Api\V1\UpdateSkillRequest;
use App\Http\Resources\Api\V1\SkillResource;
use App\Models\Skill;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $skills = Skill::where('user_id', $user->id)
            ->orderBy('category', 'asc')
            ->orderBy('name', 'asc')
            ->get();

        return ApiResponse::success(
            data: SkillResource::collection($skills),
            message: 'Skills retrieved.',
            code: 'SKILLS_RETRIEVED',
        );
    }

    public function publicIndex()
    {
        $skills = Skill::orderBy('category', 'asc')
            ->orderBy('name', 'asc')
            ->get();

        return ApiResponse::success(
            data: SkillResource::collection($skills),
            message: 'Public skills retrieved.',
            code: 'PUBLIC_SKILLS_RETRIEVED',
        );
    }

    public function store(StoreSkillRequest $request)
    {
        $user = $request->user();
        $skill = Skill::create(array_merge($request->validated(), [
            'user_id' => $user->id,
        ]));

        return ApiResponse::success(
            data: new SkillResource($skill),
            message: 'Skill created.',
            code: 'SKILL_CREATED',
            status: 201,
        );
    }

    public function show(Request $request, Skill $skill)
    {
        if ($skill->user_id !== $request->user()->id) {
            return ApiResponse::forbidden();
        }

        return ApiResponse::success(
            data: new SkillResource($skill),
            message: 'Skill retrieved.',
            code: 'SKILL_RETRIEVED',
        );
    }

    public function update(UpdateSkillRequest $request, Skill $skill)
    {
        if ($skill->user_id !== $request->user()->id) {
            return ApiResponse::forbidden();
        }

        $skill->update($request->validated());

        return ApiResponse::success(
            data: new SkillResource($skill),
            message: 'Skill updated.',
            code: 'SKILL_UPDATED',
        );
    }

    public function destroy(Request $request, Skill $skill)
    {
        if ($skill->user_id !== $request->user()->id) {
            return ApiResponse::forbidden();
        }

        $skill->delete();

        return ApiResponse::success(
            message: 'Skill deleted.',
            code: 'SKILL_DELETED',
        );
    }
}


