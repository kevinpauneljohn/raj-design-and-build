<?php

namespace App\Http\Controllers;

use App\Models\Criteria;
use App\Http\Requests\StoreCriteriaRequest;
use App\Http\Requests\UpdateCriteriaRequest;
use App\Services\CriteriaService;

class CriteriaController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view criteria')->only(['index','allCriteria']);
        $this->middleware('permission:add criteria')->only(['store']);
        $this->middleware('permission:edit criteria')->only(['edit','update']);
        $this->middleware('permission:delete criteria')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.criteria.index');
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
    public function store(StoreCriteriaRequest $request, CriteriaService $criteriaService)
    {
        return $criteriaService->saveCriteria($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(Criteria $criterion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Criteria $criterion)
    {
        return $criterion;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCriteriaRequest $request, int $criteria, CriteriaService $criteriaService)
    {
        return $criteriaService->updateCriteria($request->all(), $criteria);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Criteria $criterion)
    {
        return $criterion->delete() ?
            response()->json(['success' => true, 'message' => 'Criteria removed'], 200) :
            response()->json(['success' => false, 'message' => 'Criteria not found'], 404);
    }

    public function allCriteria(CriteriaService $criteriaService)
    {
        return $criteriaService->all_criteria_in_table_lists();
    }
}
