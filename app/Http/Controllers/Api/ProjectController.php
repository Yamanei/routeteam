<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ProjectController as ControllersProjectController;
use App\Http\Requests\ShowProjectRequest;
use App\Models\Project;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\ProjectResource;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ProjectResource::collection(Project::paginate(config('app.pagination.perpage')));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $filter = $request->input('search');

        $projects = ProjectResource::collection(Project::query()
            ->whereJsonContains('info->name', $filter)
            ->orWhere('subject', 'LIKE', '%' . $filter . '%')
            ->paginate(config('app.pagination.perpage')));

        if ($projects->isEmpty()) {
            (new ControllersProjectController)->search($filter);
        }

        return $projects;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $project->delete();
        return response()->json(['message' => 'Проект удален']);
    }
}
