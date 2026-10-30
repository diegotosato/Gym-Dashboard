<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'type',
        'start_date',
        'base_end_date',
        'status',
        'client_id',
    ];

    protected $casts = [
        'start_date' => 'date:d-m-Y',
        'base_end_date' => 'date:d-m-Y',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function suspensions()
    {
        return $this->hasMany(Suspension::class);
    }

    // Vero se oggi cade dentro una sospensione (end_date null = sospensione ancora in corso)
    public function isSuspended(): bool
    {
        $today = Carbon::today();

        foreach ($this->suspensions as $suspension) {
            if ($suspension->start_date <= $today && $suspension->end_date >= $today) {
                return true;
            }
        }

        return false;
    }

    // Unico punto in cui si calcola lo stato: pending, suspended, expired, active
    public function currentStatus(): string
    {
        $today = Carbon::today();

        if ($this->start_date > $today) {
            return 'pending';
        }

        if ($this->isSuspended()) {
            return 'suspended';
        }

        if ($this->base_end_date <= $today) {
            return 'expired';
        }

        return 'active';
    }
}
