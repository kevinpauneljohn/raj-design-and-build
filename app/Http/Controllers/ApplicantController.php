<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Http\Requests\StoreApplicantRequest;
use App\Http\Requests\UpdateApplicantRequest;
use App\Services\ApplicantService;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class ApplicantController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:view applicant')->only(['index','allApplicants']);
        $this->middleware('permission:add applicant')->only(['store']);
        $this->middleware('permission:edit applicant')->only(['edit','update']);
        $this->middleware('permission:delete applicant')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = DB::table('roles')->whereNot(function(Builder $query){
            $query->where('name', 'super admin')
            ->orWhere('name', 'panelist');
        })->get();
        return view('dashboard.applicant.index',compact('roles'));
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
    public function store(StoreApplicantRequest $request, ApplicantService $applicantService)
    {
        return $applicantService->saveApplicant($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(Applicant $applicant)
    {
        return $applicant;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Applicant $applicant)
    {
        return $applicant;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateApplicantRequest $request, int $applicant, ApplicantService $applicantService)
    {
        return $applicantService->updateApplicant($request->all(), $applicant);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Applicant $applicant)
    {
        return $applicant->delete() ?
            response()->json(['success' => true, 'message' => 'Applicant removed'], 200) :
            response()->json(['success' => false, 'message' => 'Applicant not found'], 404);
    }

    public function allApplicants(ApplicantService $applicantService)
    {
        return $applicantService->all_applicants_in_table_lists();
    }
}
