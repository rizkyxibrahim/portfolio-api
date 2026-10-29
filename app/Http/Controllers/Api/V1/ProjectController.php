<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreProjectRequest;
use App\Http\Requests\Api\V1\UpdateProjectRequest;
use App\Http\Resources\Api\V1\ProjectResource;
use App\Models\Project;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Project::where('user_id', $user->id);

        if ($request->boolean('featured') !== null) {
            $query->where('is_featured', $request->boolean('featured'));
        }

        $projects = $query->orderBy('is_featured', 'desc')
            ->orderByDesc('created_at')
            ->paginate(15);

        return ApiResponse::success(
            data: ProjectResource::collection($projects),
            message: 'Projects retrieved.',
            code: 'PROJECTS_RETRIEVED',
        );
    }

    public function publicIndex()
    {
        $projects = Project::where('is_featured', true)
            ->orderByDesc('created_at')
            ->get();

        return ApiResponse::success(
            data: ProjectResource::collection($projects),
            message: 'Public projects retrieved.',
            code: 'PUBLIC_PROJECTS_RETRIEVED',
        );
    }

    public function store(StoreProjectRequest $request)
    {
        $user = $request->user();
        $project = Project::create(array_merge($request->validated(), [
            'user_id' => $user->id,
        ]));

        return ApiResponse::success(
            data: new ProjectResource($project),
            message: 'Project created.',
            code: 'PROJECT_CREATED',
            status: 201,
        );
    }

    public function show(Request $request, Project $project)
    {
        if ($project->user_id !== $request->user()->id) {
            return ApiResponse::forbidden();
        }

        return ApiResponse::success(
            data: new ProjectResource($project),
            message: 'Project retrieved.',
            code: 'PROJECT_RETRIEVED',
        );
    }

    public function update(UpdateProjectRequest $request, Project $project)
    {
        if ($project->user_id !== $request->user()->id) {
            return ApiResponse::forbidden();
        }

        $project->update($request->validated());

        return ApiResponse::success(
            data: new ProjectResource($project),
            message: 'Project updated.',
            code: 'PROJECT_UPDATED',
        );
    }

    public function destroy(Request $request, Project $project)
    {
        if ($project->user_id !== $request->user()->id) {
            return ApiResponse::forbidden();
        }

        $project->delete();

        return ApiResponse::success(
            message: 'Project deleted.',
            code: 'PROJECT_DELETED',
        );
    }
}



