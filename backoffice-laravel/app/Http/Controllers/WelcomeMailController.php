<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\MedicalCertificate;
use App\Models\Subscription;
use App\Models\User;
use App\Models\WorkoutProgram;
use Illuminate\Http\Request;

class WelcomeMailController extends Controller
{
    public function index()
    {
        $user = ['client_id' => 62];

        $client = Client::where('id', $user['client_id'])->first();

        $sub = Subscription::where('client_id', $user['client_id'])
            ->orderBy('start_date', 'desc')
            ->first();

        $medical_certificate = MedicalCertificate::where('client_id', $user['client_id'])
            ->orderBy('medical_certificate_start_date', 'desc')
            ->first();

        $workouts = WorkoutProgram::where('client_id', $user['client_id'])
            ->with('weeks.sessions.exercises')
            ->get();

        $trainer = null;

        if ($workouts->isNotEmpty()) {
            $trainer = User::where('id', $workouts->first()->trainer_id)->first();
        }

        $object = [
            'client' => $client,
            'sub' => $sub,
            'sub_status' => $sub->currentStatus(),
            'medical_certificate' => $medical_certificate,
            'medical_certificate_status' => $medical_certificate->currentStatus(),
            'trainer' => $trainer,
            'workouts' => $workouts,
        ];

        return view('credentials', compact('object'));
    }
}
