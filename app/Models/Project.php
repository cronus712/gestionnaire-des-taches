<?php

namespace App\Models;

use App\Models\Task;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory, SoftDeletes ;

    protected $fillable = [
       'name',
       'detail'
    ];

    public function tasks()
    {
    	return $this->hasMany(Task::class);
    }

    // public function users()
    // {
    //     return $this->belongsToMany(User::class);
    // }

    protected static function booted()
    {   parent::boot();
        static::deleted(function ($project) {
            $project->tasks()->delete();
        });
    }
}
