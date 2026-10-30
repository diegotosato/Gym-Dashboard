<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Suspension extends Model
{
    protected $fillable = [
        'subscription_id',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'date:d-m-Y',
        'end_date' => 'date:d-m-Y',
    ];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}
