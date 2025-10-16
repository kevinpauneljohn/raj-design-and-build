<?php

namespace App\Services;

use App\Models\Project;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ProjectService
{
    public function saveProject(array $data): \Illuminate\Http\JsonResponse
    {
        if(Project::create($data))
        {
            return response()->json([
                'success' => true,
                'message' => 'Project added successfully',
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Project not added'
        ]);
    }

    public function updateProject(array $data, string $id): \Illuminate\Http\JsonResponse
    {
        $client = Project::findOrFail($id)->fill($data);
        if($client->isDirty())
        {
            if($client->save())
            {
                return response()->json([
                    'success' => true,
                    'message' => 'Project updated successfully!'
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => 'Project was not updated!'
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'No changes were made!'
        ]);
    }

    public function all_project_in_table_lists()
    {
        $projects = Project::all();
        return DataTables::of($projects)
            ->editColumn('created_at', function ($project) {
                return $project->created_at->format('M-d-Y h:i A');
            })
            ->editColumn('name', function ($project) {
                return '<a href="'.route('project.show',['project' => $project->id]).'">'.ucwords($project->name).'</a>';
            })
            ->editColumn('address', function ($project) {
                return ucwords($project->address);
            })
            ->editColumn('price', function ($project) {
                return number_format($project->price,2);
            })
            ->addColumn('client', function ($project) {
                return '<a href="'.route('client.show',['client' => $project->client_id]).'">'.ucwords($project->client->full_name).'</a>';
            })
            ->addColumn('assigned_users', function ($project) {
                $data = '';
                foreach ($project->users as $user) {
                    $data .= '<span class="badge bg-purple mr-1">'.$user->full_name.'</span>';
                }
                return $data;
            })
            ->editColumn('user_id', function ($project) {
                return ucwords($project->user->full_name);
            })
            ->addColumn('action', function ($project) {
                $action = '<div class="btn-group" role="group">
                        <a class="btn dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                            <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                          </a>
                        <div class="dropdown-menu" role="menu">';
                if(auth()->user()->can('view project'))
                {
                    $action .= '<a href="'.route('project.show',['project' => $project->id]).'" class="dropdown-item view-project-btn text-success" id="'.$project->id.'"><i class="fa fa-folder" aria-hidden="true"></i> Manage</a>';
                }
                if(auth()->user()->can('assign project to user'))
                {
                    $action .= '<a href="#" class="dropdown-item assign-user-btn" id="'.$project->id.'"><i class="fa fa-users" aria-hidden="true"></i> Assign Users</a>';
                }
                if(auth()->user()->can('edit project'))
                {
                    $action .= '<a href="#" class="dropdown-item edit-project-btn text-primary" id="'.$project->id.'"><i class="fa fa-pencil-alt" aria-hidden="true"></i> Edit</a>';
                }
                if(auth()->user()->can('delete project'))
                {
                    $action .= '<a href="#" class="dropdown-item delete-project-btn text-danger" id="'.$project->id.'"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>';
                }
                $action .= '</div>';
                return $action;
            })
            ->rawColumns(['action','client','name','assigned_users'])
            ->make(true);
    }

    public function getAssignedUsers($project_id)
    {
        return collect(Project::findOrfail($project_id)->users)->pluck(['id']);
    }

}
