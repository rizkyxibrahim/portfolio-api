<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function indexPublic()
    {
        $projects = Project::published()
            ->where('is_featured', true)
            ->orderBy('display_order', 'desc')
            ->get();

        return ProjectResource::collection($projects);
    }

    public function showPublic($slug)
    {
        $project = Project::published()->where('slug', $slug)->firstOrFail();

        return new ProjectResource($project);
    }

    public function indexAdmin(Request $request)
    {
        $projects = Project::orderBy('display_order', 'desc')->get();

        return ProjectResource::collection($projects);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:projects',
            'excerpt' => 'nullable|string',
            'content' => 'nullable|string',
            'image' => 'nullable|string|max:255',
            'demo_url' => 'nullable|url|max:255',
            'github_url' => 'nullable|url|max:255',
            'status' => 'in:draft,published,archived',
            'is_featured' => 'sometimes|boolean',
            'published_at' => 'nullable|date',
            'display_order' => 'sometimes|integer',
        ]);

        $project = Project::create($validated);

        return response()->json(new ProjectResource($project), 201);
    }

    public function showAdmin($id)
    {
        $project = Project::findOrFail($id);

        return new ProjectResource($project);
    }

    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'slug' => 'sometimes|string|max:255|unique:projects,slug,' . $id,
            'excerpt' => 'sometimes|string',
            'content' => 'sometimes|string',
            'image' => 'sometimes|string|max:255',
            'demo_url' => 'sometimes|url|max:255',
            'github_url' => 'sometimes|url|max:255',
            'status' => 'sometimes|in:draft,published,archived',
            'is_featured' => 'sometimes|boolean',
            'published_at' => 'sometimes|date',
            'display_order' => 'sometimes|integer',
        ]);

        $project->update($validated);

        return new ProjectResource($project->fresh());
    }

    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();

        return response()->json([
            'message' => 'Proyek berhasil dihapus.',
        ]);
    }
}