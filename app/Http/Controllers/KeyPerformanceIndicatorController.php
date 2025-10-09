<?php

namespace App\Http\Controllers;

use App\Models\KeyPerformanceIndicator;
use App\Http\Requests\StoreKeyPerformanceIndicatorRequest;
use App\Http\Requests\UpdateKeyPerformanceIndicatorRequest;

class KeyPerformanceIndicatorController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:view kpi')->only(['index','allKpi']);
        $this->middleware('permission:add kpi')->only(['store']);
        $this->middleware('permission:edit kpi')->only(['edit','update']);
        $this->middleware('permission:delete kpi')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard');
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
    public function store(StoreKeyPerformanceIndicatorRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(KeyPerformanceIndicator $keyPerformanceIndicator)
    {
        return view('dashboard.kpi.show')->with([
            'keyPerformanceIndicators' => $keyPerformanceIndicator
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KeyPerformanceIndicator $keyPerformanceIndicator)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateKeyPerformanceIndicatorRequest $request, KeyPerformanceIndicator $keyPerformanceIndicator)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KeyPerformanceIndicator $keyPerformanceIndicator)
    {
        //
    }

    public function allKpi()
    {

    }
}
