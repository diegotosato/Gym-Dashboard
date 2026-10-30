<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkoutSession extends Model
{
    protected $fillable = [
        'workout_week_id',
    ];

    public function week()
    {
        return $this->belongsTo(WorkoutWeek::class, 'workout_week_id');
    }

    public function exercises()
    {
        return $this->hasMany(WorkoutExercise::class);
    }
}
