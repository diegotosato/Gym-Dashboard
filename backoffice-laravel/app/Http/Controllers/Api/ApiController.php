<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClientCredential;
use App\Models\MedicalCertificate;
use App\Models\Subscription;
use App\Models\User;
use App\Models\WorkoutProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ApiController extends Controller
{
    public function client_login(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $find_user = ClientCredential::where('username', $request->username)->get();

        if ($find_user->isEmpty()) {
            return response()->json([
                'error' => 'Non esiste nessun utente con questo username',
            ], 404);
        }

        $user = $find_user[0];

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'error' => 'Password errata',
            ], 401);
        }

        $token = $user->createToken('mobile-app')->plainTextToken;

        $client = Client::where('id', $user->client_id)->first();

        $sub = Subscription::where('client_id', $user->client_id)->orderBy('start_date', 'desc')->first();

        $medical_certificate = MedicalCertificate::where('client_id', $user->client_id)->orderBy('medical_certificate_start_date', 'desc')->first();

        $workouts = WorkoutProgram::where('client_id', $user->client_id)
            ->with('weeks.sessions.exercises')
            ->get();

        if ($workouts->isNotEmpty()) {
            $find_trainers_id = $workouts[0]->trainer_id;
            $trainer = User::where('id', $find_trainers_id)->first();
        } else {
            $workouts = null;
            $trainer = null;
        }

        return response()->json([
            'message' => 'Username e password validi',
            'token' => $token,
            'user' => [
                'client' => $client,
                'sub' => $sub,
                'sub_status' => $sub->currentStatus(),
                'medical_certificate' => $medical_certificate,
                'medical_certificate_status' => $medical_certificate->currentStatus(),
                'trainer' => $trainer,
                'workouts' => $workouts,
            ]
        ], 200);
    }
}
