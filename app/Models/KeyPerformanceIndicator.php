<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeyPerformanceIndicator extends Model
{
    use HasFactory;

    protected $fillable = ['key_form_id','category','description','rating_guide'];
}
