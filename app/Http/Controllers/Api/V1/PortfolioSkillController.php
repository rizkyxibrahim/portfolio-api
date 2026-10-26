<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\PortfolioSkill;
use App\Support\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
            data: $skills,
            message: 'Portfolio skills retrieved.',
            code: 'PORTFOLIO_SKILLS_RETRIEVED',
        );
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'category' => 'nullable|string|max:50',
            'level' => 'sometimes|in:beginner,intermediate,advanced,expert',
            'years_of_experience' => 'nullable|integer|min:0',
        ]);
        if ($validator->fails()) {
            return ApiResponse::validationError($validator->errors()->toArray());
        }
        $skill = PortfolioSkill::create(array_merge($validator->validated(), [
            'user_id' => $user->id,
        ]));
        return ApiResponse::success(
            data: $skill,
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
            data: $skill,
            message: 'Portfolio skill retrieved.',
            code: 'PORTFOLIO_SKILL_RETRIEVED',
        );
    }

    public function update(Request $request, PortfolioSkill $skill)
    {
        if ($skill->user_id !== $request->user()->id) {
            return ApiResponse::forbidden();
        }
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:100',
            'category' => 'nullable|string|max:50',
            'level' => 'sometimes|in:beginner,intermediate,advanced,expert',
            'years_of_experience' => 'nullable|integer|min:0',
        ]);
        if ($validator->fails()) {
            return ApiResponse::validationError($validator->errors()->toArray());
        }
        $skill->update($validator->validated());
        return ApiResponse::success(
            data: $skill,
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
