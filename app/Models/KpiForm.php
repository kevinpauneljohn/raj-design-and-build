<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KpiForm extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name','description','assessment_from','assessment_to'];

    protected $casts = [
        'assessment_from' => 'json',
    ];
}
