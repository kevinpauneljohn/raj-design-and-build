<?php

namespace App\Services;

use App\Models\Criteria;
use Yajra\DataTables\Facades\DataTables;

class CriteriaService
{
    public function saveCriteria(array $data)
    {
        if(Criteria::create($data))
        {
            return response()->json([
                'success' => true,
                'message' => 'Criteria added successfully',
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Criteria not added'
        ]);
    }

    public function updateCriteria(array $data, string $id)
    {
        $applicant = Criteria::findOrFail($id)->fill($data);
        if($applicant->isDirty())
        {
            if($applicant->save())
            {
                return response()->json([
                    'success' => true,
                    'message' => 'Criteria updated successfully!'
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => 'Criteria was not updated!'
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'No changes were made!'
        ]);
    }

    public function all_criteria_in_table_lists()
    {
        $criterion = Criteria::all();
        return DataTables::of($criterion)
            ->editColumn('created_at', function ($criteria) {
                return $criteria->created_at->format('M-d-Y h:i A');
            })
            ->addColumn('action', function ($criteria) {
                $action = '';
                if(auth()->user()->can('edit criteria'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-primary edit-criteria-btn mr-1 mb-1" id="'.$criteria->id.'">Edit</a>';
                }
                if(auth()->user()->can('delete criteria'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-danger delete-criteria-btn mr-1 mb-1" id="'.$criteria->id.'">Delete</a>';
                }
                return $action;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
