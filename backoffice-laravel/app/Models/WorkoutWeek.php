<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkoutWeek extends Model
{
    protected $fillable = [
        'workout_program_id',
        'week_number',
    ];

    public function program()
    {
        return $this->belongsTo(WorkoutProgram::class, 'workout_program_id');
    }

    public function sessions()
    {
        return $this->hasMany(WorkoutSession::class);
    }
}
