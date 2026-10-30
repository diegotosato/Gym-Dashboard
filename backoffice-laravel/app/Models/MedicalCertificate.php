<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Model;

class MedicalCertificate extends Model
{
    protected $fillable = [
        'client_id',
        'medical_certificate_start_date',
        'medical_certificate_expiry_date',
    ];

    protected $casts = [
        'medical_certificate_start_date' => 'date:d-m-Y',
        'medical_certificate_expiry_date' => 'date:d-m-Y',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function currentStatus(): string
    {
        if ($this->medical_certificate_expiry_date < new DateTime('today')) {
            return 'expired';
        }

        return 'active';
    }
}
