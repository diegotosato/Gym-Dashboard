<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkoutProgram extends Model
{
    protected $fillable = [
        'client_id',
        'trainer_id',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function trainer()
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }

    public function weeks()
    {
        return $this->hasMany(WorkoutWeek::class);
    }
}
