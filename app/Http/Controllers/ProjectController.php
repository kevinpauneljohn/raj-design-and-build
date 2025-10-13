<?php

namespace App\Http\Controllers;

use App\Http\Middleware\CheckIfUserWasAllowedToAccessProject;
use App\Models\Client;
use App\Models\Project;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\User;
use App\Services\PhaseService;
use App\Services\ProjectService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view project')->only(['index','allProjects']);
        $this->middleware('permission:add project')->only(['store']);
        $this->middleware('permission:edit project')->only(['edit','update']);
        $this->middleware('permission:delete project')->only(['destroy']);
        $this->middleware(CheckIfUserWasAllowedToAccessProject::class)->only(['show']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.projects.index')->with([
            'clients' => Client::all(),
            'users' => User::all()
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
        return $projectService->saveProject(collect($request->all())->merge(['user_id' => auth()->id()])->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project, PhaseService $phaseService)
    {
        return view('dashboard.projects.profile')->with([
            'project' => $project,
            'remaining_percentage' => $phaseService->check_phase_remaining_percentage($project->id),
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

    public function assignedUsers($project, ProjectService $projectService)
    {
        return $projectService->getAssignedUsers($project);
    }

    public function assignUser(Request $request, $project)
    {
        DB::table('project_user')->where('project_id',$project)->delete();
        if(!is_null($request->users))
        {
            foreach ($request->users as $user) {
                DB::table('project_user')->updateOrInsert([
                    'project_id' => $project,
                    'user_id' => $user,
                ]);
            }
            return response()->json(['success' => true, 'message' => 'Users assigned']);
        }
        return response()->json(['success' => false, 'message' => 'No assigned users']);
    }
}
