<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Score extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['applicant_id','user_id','criteria_id','score','note'];

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }
}
