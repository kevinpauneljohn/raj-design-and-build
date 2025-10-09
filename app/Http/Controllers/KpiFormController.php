<?php

namespace App\Http\Controllers;

use App\Models\KpiForm;
use App\Http\Requests\StoreKpiFormRequest;
use App\Http\Requests\UpdateKpiFormRequest;
use App\Services\KpiFormService;
use Spatie\Permission\Models\Role;

class KpiFormController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:view kpi')->only(['index','allKpiForms']);
        $this->middleware('permission:add kpi')->only(['store']);
        $this->middleware('permission:edit kpi')->only(['edit','update']);
        $this->middleware('permission:delete kpi')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.kpi_forms.index')
            ->with([
                'roles' => Role::whereNotIn('name',[
                    'super admin'
                ])->get(),
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
    public function store(StoreKpiFormRequest $request, KpiFormService $kpiFormService)
    {
        return $kpiFormService->saveKpiForm($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(KpiForm $kpiForm)
    {
        return view('dashboard.kpi_forms.show')->with([
            'kpiForm' => $kpiForm
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KpiForm $kpiForm)
    {
        return $kpiForm;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateKpiFormRequest $request, int $kpiForm, KpiFormService $kpiFormService)
    {
        return $kpiFormService->updateKpiForm($request->all(), $kpiForm);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KpiForm $kpiForm)
    {
        return $kpiForm->delete() ?
            response()->json(['success' => true, 'message' => 'KPI Form removed'], 200) :
            response()->json(['success' => false, 'message' => 'KPI Form not found'], 404);
    }

    public function allKpiForms(KpiFormService $kpiFormService): \Illuminate\Http\JsonResponse
    {
        return $kpiFormService->all_kpi_forms_in_table_lists();
    }
}
