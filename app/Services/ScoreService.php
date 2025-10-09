<?php

namespace App\Services;

use App\Models\Applicant;
use App\Models\Score;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ScoreService
{
    public function saveScore(array $data)
    {
        $scores = [];
        foreach ($data['criteria_id'] as $key => $value) {
            $score = [
                'applicant_id' => $data['applicant_id'],
                'user_id' => auth()->id(),
                'criteria_id' => $value,
                'score' => $data['score'][$key],
                'note' => $data['note'][$key],
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $scores[$key] = $score;
        }

        try {
            DB::transaction(function () use ($scores) {
                DB::table('scores')->insert($scores);
            });
        }catch (\Exception $exception){
            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
        }
        return response()->json(['success' => true, 'message' => 'Score saved successfully.']);
    }

    public function all_scores_in_table_lists()
    {
//        $applicants = Applicant::withExists('scores')
//            ->with(['scores:applicant_id,user_id,score'])
//            ->having('scores_exists',true)
//            ->get();

        $scores = Score::all();
        $applicants = $scores->groupBy('user_id');
        return DataTables::of($applicants)
//            ->editColumn('created_at', function ($applicant) {
//                return $applicant->created_at->format('M/d/Y');
//            })
//            ->addColumn('average_score', function ($applicant) {
//                return number_format(collect($applicant->scores)->avg('score'),2);
//            })
//            ->addColumn('panelist', function ($applicant) {
//                $user_id = collect(collect($applicant->scores)->first())->toArray()['user_id'];
//                return ucwords(User::findOrFail($user_id)->full_name);
//            })
            ->rawColumns(['action'])
            ->make(true);
    }

    private function averageScore()
    {

    }
}
