<?php

namespace App\Services;

use App\Models\Phase;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

class PhaseService
{
    private function project($project_id)
    {
        return Phase::where('project_id',$project_id);
    }
    public function savePhase(array $data): \Illuminate\Http\JsonResponse
    {
        if(Phase::create($data))
        {
            return response()->json([
                'success' => true,
                'message' => 'Phase added successfully',
                'remaining_percentage' => $this->check_phase_remaining_percentage($data['project_id'])
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Phase not added'
        ]);
    }

    public function check_phase_remaining_percentage($project_id)
    {
        //check if project_id already exists
        if($this->project_exists($project_id))
        {
            return 100 - $this->get_total_percentage($project_id);
        }
        return 100;
    }

    public function get_total_percentage($project_id)
    {
        return $this->project($project_id)->sum('percentage');
    }

    public function project_exists($project_id): bool
    {
        return $this->project($project_id)->count() > 0;
    }

    public function updatePhase(array $data, string $id): \Illuminate\Http\JsonResponse
    {
        $phase = Phase::findOrFail($id)->fill($data);
        if($phase->isDirty())
        {
            if($phase->save())
            {
                return response()->json([
                    'success' => true,
                    'message' => 'Phase updated successfully!',
                    'remaining_percentage' => $this->check_phase_remaining_percentage($phase->project_id)
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => 'Phase was not updated!'
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'No changes were made!'
        ]);
    }

    public function all_phases_in_table_lists($project_id)
    {
        $phases = Phase::all();
        return DataTables::of($phases)
            ->editColumn('created_at', function ($phase) {
                return $phase->created_at->format('M-d-Y h:i A');
            })
            ->editColumn('name', function ($phase) {
                return '<span class="text-primary text-bold">'.ucwords($phase->name).'</span>';
            })
            ->editColumn('percentage', function ($phase) {
                return '<span class="text-success text-bold">'.$phase->percentage.'%</span>';
            })
            ->addColumn('timeline', function ($phase) {

                return is_null($phase->start_date) || is_null($phase->end_date) ? ''
                    : '<span class="text-purple text-bold">'.Carbon::parse($phase->start_date)->format('M-d-Y').'</span> to <span class="text-purple text-bold">'.Carbon::parse($phase->end_date)->format('M-d-Y').'</span>';
            })
            ->addColumn('action', function ($phase) {
                $action = '<div class="btn-group" role="group">
                        <a class="btn dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                            <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                          </a>
                        <div class="dropdown-menu" role="menu">';
                if(auth()->user()->can('edit client'))
                {
                    $action .= '<a href="#" class="edit-phase-btn dropdown-item text-primary" id="'.$phase->id.'"><i class="fa fa-pencil-alt" aria-hidden="true"></i> Edit</a>';
                }
                if(auth()->user()->can('delete client'))
                {
                    $action .= '<a href="#" class="delete-phase-btn dropdown-item text-danger" id="'.$phase->id.'"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>';
                }
                $action .= '</div>';
                return $action;
            })
            ->rawColumns(['action','name', 'percentage','timeline'])
            ->with([
                'total_percentage' => $this->get_total_percentage($project_id)
            ])
            ->make(true);
    }
}
