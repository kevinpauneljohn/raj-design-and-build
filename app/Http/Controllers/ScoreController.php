<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\Criteria;
use App\Models\Score;
use App\Http\Requests\StoreScoreRequest;
use App\Http\Requests\UpdateScoreRequest;
use App\Services\ScoreService;

class ScoreController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:score applicant')->only(['index','store','allScores']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $applicants = Applicant::all();
        $criterion = Criteria::all();
        return view('dashboard.score.index',compact('applicants','criterion'));
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
    public function store(StoreScoreRequest $request, ScoreService $scoreService)
    {
        return $scoreService->saveScore($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(Score $score)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Score $score)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateScoreRequest $request, Score $score)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Score $score)
    {
        //
    }

    public function allScores(ScoreService $scoreService)
    {
        return $scoreService->all_scores_in_table_lists();
    }
}
