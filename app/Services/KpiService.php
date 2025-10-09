<?php

namespace App\Services;

use App\Models\KeyPerformanceIndicator;
use Yajra\DataTables\Facades\DataTables;

class KpiService
{
    public function saveKpi(array $data)
    {
        if(KeyPerformanceIndicator::create($data))
        {
            return response()->json([
                'success' => true,
                'message' => 'KPI added successfully',
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'KPI was not added'
        ]);
    }

    public function updateKpi(array $data, string $id)
    {
        $applicant = KeyPerformanceIndicator::findOrFail($id)->fill($data);
        if($applicant->isDirty())
        {
            if($applicant->save())
            {
                return response()->json([
                    'success' => true,
                    'message' => 'Kpi updated successfully!'
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => 'Kpi was not updated!'
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'No changes were made!'
        ]);
    }

    public function all_kpi_in_table_lists(): \Illuminate\Http\JsonResponse
    {
        $kpis = KeyPerformanceIndicator::all();
        return DataTables::of($kpis)
            ->editColumn('created_at', function ($kpi) {
                return $kpi->created_at->format('M/d/Y');
            })
            ->editColumn('description', function ($kpi) {
                return nl2br($kpi->description);
            })
            ->addColumn('action', function ($kpi) {
                $action = '';
                if(auth()->user()->can('view kpi'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-success view-kpi-btn mr-1 mb-1" id="'.$kpi->id.'">View</a>';
                }
                if(auth()->user()->can('edit kpi'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-primary edit-kpi-btn mr-1 mb-1" id="'.$kpi->id.'">Edit</a>';
                }
                if(auth()->user()->can('delete kpi'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-danger delete-kpi-btn mr-1 mb-1" id="'.$kpi->id.'">Delete</a>';
                }
                return $action;
            })
            ->rawColumns(['action','description'])
            ->make(true);
    }
}
