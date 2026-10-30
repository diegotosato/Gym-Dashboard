<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Ai\Concerns\HasConversations;

class Client extends Model
{
    use HasConversations;

    protected $fillable = [
        'first_name',
        'last_name',
        'slug',
        'gender',
        'email',
        'phone',
        'date_of_birth',
        'address',
        'tax_code',
        'role_id',
        'notes',
    ];

    protected $casts = [
        'date_of_birth' => 'date:d-m-Y',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function medical_certificates()
    {
        return $this->hasMany(MedicalCertificate::class)->orderBy('medical_certificate_start_date');
    }

    public function workout_programs()
    {
        return $this->hasMany(WorkoutProgram::class);
    }

    public function client_credential()
    {
        return $this->hasOne(ClientCredential::class);
    }
}
