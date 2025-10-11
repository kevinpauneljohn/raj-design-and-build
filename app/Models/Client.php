<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'firstname','middlename','lastname','date_of_birth','mobile_number','email','address'
    ];

    protected $appends = ['full_name'];

    public function getFullNameAttribute(): string
    {
        return ucwords($this->attributes['firstname']) . ' ' . ucwords($this->attributes['lastname']);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}
