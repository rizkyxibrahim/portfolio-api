<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\PortfolioProject;
use App\Support\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PortfolioProjectController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $query = PortfolioProject::where('user_id', $user->id);
        if ($request->boolean('featured') !== null) {
            $query->where('is_featured', $request->boolean('featured'));
        }
        $projects = $query->orderBy('is_featured', 'desc')
            ->orderByDesc('created_at')
            ->paginate(15);
        return ApiResponse::success(
            data: $projects,
            message: 'Portfolio projects retrieved.',
            code: 'PORTFOLIO_PROJECTS_RETRIEVED',
        );
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image_url' => 'nullable|url',
            'github_url' => 'nullable|url',
            'live_url' => 'nullable|url',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_featured' => 'sometimes|boolean',
        ]);
        if ($validator->fails()) {
            return ApiResponse::validationError($validator->errors()->toArray());
        }
        $project = PortfolioProject::create(array_merge($validator->validated(), [
            'user_id' => $user->id,
        ]));
        return ApiResponse::success(
            data: $project,
            message: 'Portfolio project created.',
            code: 'PORTFOLIO_PROJECT_CREATED',
            status: 201,
        );
    }

    public function show(Request $request, PortfolioProject $project)
    {
        if ($project->user_id !== $request->user()->id) {
            return ApiResponse::forbidden();
        }
        return ApiResponse::success(
            data: $project,
            message: 'Portfolio project retrieved.',
            code: 'PORTFOLIO_PROJECT_RETRIEVED',
        );
    }

    public function update(Request $request, PortfolioProject $project)
    {
        if ($project->user_id !== $request->user()->id) {
            return ApiResponse::forbidden();
        }
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'image_url' => 'nullable|url',
            'github_url' => 'nullable|url',
            'live_url' => 'nullable|url',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_featured' => 'sometimes|boolean',
        ]);
        if ($validator->fails()) {
            return ApiResponse::validationError($validator->errors()->toArray());
        }
        $project->update($validator->validated());
        return ApiResponse::success(
            data: $project,
            message: 'Portfolio project updated.',
            code: 'PORTFOLIO_PROJECT_UPDATED',
        );
    }

    public function destroy(Request $request, PortfolioProject $project)
    {
        if ($project->user_id !== $request->user()->id) {
            return ApiResponse::forbidden();
        }
        $project->delete();
        return ApiResponse::success(
            message: 'Portfolio project deleted.',
            code: 'PORTFOLIO_PROJECT_DELETED',
        );
    }
}
