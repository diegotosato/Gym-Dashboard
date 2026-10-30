<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkoutExercise extends Model
{
    protected $fillable = [
        'workout_session_id',
        'exercise_name',
        'sets',
        'reps',
        'weight',
        'notes',
    ];

    public function week()
    {
        return $this->belongsTo(WorkoutSession::class, 'workout_session_id');
    }
}
