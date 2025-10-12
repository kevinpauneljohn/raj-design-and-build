<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Project;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Services\PhaseService;
use App\Services\ProjectService;
use App\Services\SupplierService;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view project')->only(['index','allProjects']);
        $this->middleware('permission:add project')->only(['store']);
        $this->middleware('permission:edit project')->only(['edit','update']);
        $this->middleware('permission:delete project')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.projects.index')->with([
            'clients' => Client::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request, ProjectService $projectService): \Illuminate\Http\JsonResponse
    {
        return $projectService->saveProject($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project, PhaseService $phaseService)
    {
        return view('dashboard.projects.profile')->with([
            'project' => $project,
            'remaining_percentage' => $phaseService->check_phase_remaining_percentage($project->id)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        return $project;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, string $project, ProjectService $projectService): \Illuminate\Http\JsonResponse
    {
        return $projectService->updateProject($request->all(), $project);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        return $project->delete() ?
            response()->json(['success' => true, 'message' => 'Project deleted'], 200) :
            response()->json(['success' => false, 'message' => 'Project not found'], 404);
    }

    public function allProjects(ProjectService $projectService): \Illuminate\Http\JsonResponse
    {
        return $projectService->all_project_in_table_lists();
    }
}
