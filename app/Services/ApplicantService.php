<?php

namespace App\Services;

use App\Http\Requests\StoreApplicantRequest;
use App\Models\Applicant;
use Yajra\DataTables\Facades\DataTables;

class ApplicantService
{
    public function saveApplicant(array $data)
    {
        if(Applicant::create($data))
        {
            return response()->json([
                'success' => true,
                'message' => 'Applicant added successfully',
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Applicant not added'
        ]);
    }

    public function updateApplicant(array $data, string $id)
    {
        $applicant = Applicant::findOrFail($id)->fill($data);
        if($applicant->isDirty())
        {
            if($applicant->save())
            {
                return response()->json([
                    'success' => true,
                    'message' => 'Applicant updated successfully!'
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => 'Applicant was not updated!'
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'No changes were made!'
        ]);
    }

    public function all_applicants_in_table_lists()
    {
        $applicants = Applicant::all();
        return DataTables::of($applicants)
            ->editColumn('created_at', function ($applicant) {
                return $applicant->created_at->format('M/d/Y');
            })
            ->editColumn('address', function ($applicant) {
                return ucwords($applicant->address);
            })
            ->addColumn('action', function ($applicant) {
                $action = '';
                if(auth()->user()->can('edit applicant'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-primary edit-applicant-btn mr-1 mb-1" id="'.$applicant->id.'">Edit</a>';
                }
                if(auth()->user()->can('delete applicant'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-danger delete-applicant-btn mr-1 mb-1" id="'.$applicant->id.'">Delete</a>';
                }
                return $action;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
