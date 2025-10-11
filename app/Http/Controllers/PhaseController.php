<?php

namespace App\Http\Controllers;

use App\Models\Phase;
use App\Http\Requests\StorePhaseRequest;
use App\Http\Requests\UpdatePhaseRequest;
use App\Services\PhaseService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PhaseController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view phase')->only(['index','allPhases']);
        $this->middleware('permission:add phase')->only(['store']);
        $this->middleware('permission:edit phase')->only(['edit','update']);
        $this->middleware('permission:delete phase')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(StorePhaseRequest $request, PhaseService $phaseService)
    {
        $date = explode(" - ",$request->timeline);

        $data = collect($request->all())->merge([
            'start_date' => Carbon::parse($date[0]),
            'end_date' => Carbon::parse($date[1]),
        ])->toArray();
        return $phaseService->savePhase($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(Phase $phase)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Phase $phase)
    {
        return $phase;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePhaseRequest $request, int $phase, PhaseService $phaseService)
    {
        return $phaseService->updatePhase($request->all(), $phase);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Phase $phase)
    {
        return $phase->delete() ?
            response()->json(['success' => true, 'message' => 'Phase deleted'], 200) :
            response()->json(['success' => false, 'message' => 'Phase not found'], 404);
    }

    public function allPhases(PhaseService $phaseService): \Illuminate\Http\JsonResponse
    {
        return $phaseService->all_phases_in_table_lists();
    }
}
