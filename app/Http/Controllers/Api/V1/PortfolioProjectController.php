<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StorePortfolioProjectRequest;
use App\Http\Requests\Api\V1\UpdatePortfolioProjectRequest;
use App\Http\Resources\Api\V1\PortfolioProjectResource;
use App\Models\PortfolioProject;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

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
            data: PortfolioProjectResource::collection($projects),
            message: 'Portfolio projects retrieved.',
            code: 'PORTFOLIO_PROJECTS_RETRIEVED',
        );
    }

    public function publicIndex()
    {
        $projects = PortfolioProject::where('is_featured', true)
            ->orderByDesc('created_at')
            ->get();

        return ApiResponse::success(
            data: PortfolioProjectResource::collection($projects),
            message: 'Public projects retrieved.',
            code: 'PUBLIC_PROJECTS_RETRIEVED',
        );
    }

    public function store(StorePortfolioProjectRequest $request)
    {
        $user = $request->user();
        $project = PortfolioProject::create(array_merge($request->validated(), [
            'user_id' => $user->id,
        ]));

        return ApiResponse::success(
            data: new PortfolioProjectResource($project),
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
            data: new PortfolioProjectResource($project),
            message: 'Portfolio project retrieved.',
            code: 'PORTFOLIO_PROJECT_RETRIEVED',
        );
    }

    public function update(UpdatePortfolioProjectRequest $request, PortfolioProject $project)
    {
        if ($project->user_id !== $request->user()->id) {
            return ApiResponse::forbidden();
        }

        $project->update($request->validated());

        return ApiResponse::success(
            data: new PortfolioProjectResource($project),
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
