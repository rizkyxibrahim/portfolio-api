<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StorePortfolioSkillRequest;
use App\Http\Requests\Api\V1\UpdatePortfolioSkillRequest;
use App\Http\Resources\Api\V1\PortfolioSkillResource;
use App\Models\PortfolioSkill;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class PortfolioSkillController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $skills = PortfolioSkill::where('user_id', $user->id)
            ->orderBy('category', 'asc')
            ->orderBy('name', 'asc')
            ->get();

        return ApiResponse::success(
            data: PortfolioSkillResource::collection($skills),
            message: 'Portfolio skills retrieved.',
            code: 'PORTFOLIO_SKILLS_RETRIEVED',
        );
    }

    public function publicIndex()
    {
        $skills = PortfolioSkill::orderBy('category', 'asc')
            ->orderBy('name', 'asc')
            ->get();

        return ApiResponse::success(
            data: PortfolioSkillResource::collection($skills),
            message: 'Public skills retrieved.',
            code: 'PUBLIC_SKILLS_RETRIEVED',
        );
    }

    public function store(StorePortfolioSkillRequest $request)
    {
        $user = $request->user();
        $skill = PortfolioSkill::create(array_merge($request->validated(), [
            'user_id' => $user->id,
        ]));

        return ApiResponse::success(
            data: new PortfolioSkillResource($skill),
            message: 'Portfolio skill created.',
            code: 'PORTFOLIO_SKILL_CREATED',
            status: 201,
        );
    }

    public function show(Request $request, PortfolioSkill $skill)
    {
        if ($skill->user_id !== $request->user()->id) {
            return ApiResponse::forbidden();
        }

        return ApiResponse::success(
            data: new PortfolioSkillResource($skill),
            message: 'Portfolio skill retrieved.',
            code: 'PORTFOLIO_SKILL_RETRIEVED',
        );
    }

    public function update(UpdatePortfolioSkillRequest $request, PortfolioSkill $skill)
    {
        if ($skill->user_id !== $request->user()->id) {
            return ApiResponse::forbidden();
        }

        $skill->update($request->validated());

        return ApiResponse::success(
            data: new PortfolioSkillResource($skill),
            message: 'Portfolio skill updated.',
            code: 'PORTFOLIO_SKILL_UPDATED',
        );
    }

    public function destroy(Request $request, PortfolioSkill $skill)
    {
        if ($skill->user_id !== $request->user()->id) {
            return ApiResponse::forbidden();
        }

        $skill->delete();

        return ApiResponse::success(
            message: 'Portfolio skill deleted.',
            code: 'PORTFOLIO_SKILL_DELETED',
        );
    }
}
