<?php

namespace App\Services;

use App\Models\KpiForm;
use Yajra\DataTables\Facades\DataTables;

class KpiFormService
{
    public function saveKpiForm(array $data)
    {
        if(KpiForm::create($data))
        {
            return response()->json([
                'success' => true,
                'message' => 'KPI Form added successfully',
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'KPI Form was not added'
        ]);
    }

    public function updateKpiForm(array $data, string $id)
    {
        $applicant = KpiForm::findOrFail($id)->fill($data);
        if($applicant->isDirty())
        {
            if($applicant->save())
            {
                return response()->json([
                    'success' => true,
                    'message' => 'Kpi form updated successfully!'
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => 'Kpi form was not updated!'
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'No changes were made!'
        ]);
    }

    public function all_kpi_forms_in_table_lists(): \Illuminate\Http\JsonResponse
    {
        $kpiForms = KpiForm::all();
        return DataTables::of($kpiForms)
            ->editColumn('created_at', function ($kpiForm) {
                return $kpiForm->created_at->format('M-d-Y h:i A');
            })
            ->editColumn('description', function ($kpiForm) {
                return nl2br($kpiForm->description);
            })
            ->addColumn('action', function ($kpiForm) {
                $action = '';
                if(auth()->user()->can('view kpi'))
                {
                    $action .= '<a href="'.route('kpi-forms.show',['kpi_form' => $kpiForm->id]).'" class="btn btn-xs btn-success view-kpi-form-btn mr-1 mb-1" id="'.$kpiForm->id.'">View</a>';
                }
                if(auth()->user()->can('edit kpi'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-primary edit-kpi-form-btn mr-1 mb-1" id="'.$kpiForm->id.'">Edit</a>';
                }
                if(auth()->user()->can('delete kpi'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-danger delete-kpi-form-btn mr-1 mb-1" id="'.$kpiForm->id.'">Delete</a>';
                }
                return $action;
            })
            ->rawColumns(['action','description'])
            ->make(true);
    }
}
