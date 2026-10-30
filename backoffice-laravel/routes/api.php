<?php

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\ExerciseAgentController;
use App\Models\Client;
use App\Models\MedicalCertificate;
use App\Models\Subscription;
use App\Models\User;
use App\Models\WorkoutProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('user', function (Request $request) {
    $user = $request->user();

    $client = Client::where('id', $user->client_id)->first();

    $sub = Subscription::where('client_id', $user->client_id)
        ->orderBy('start_date', 'desc')
        ->first();

    $medical_certificate = MedicalCertificate::where('client_id', $user->client_id)
        ->orderBy('medical_certificate_start_date', 'desc')
        ->first();

    $workouts = WorkoutProgram::where('client_id', $user->client_id)
        ->with('weeks.sessions.exercises')
        ->get();

    $trainer = null;

    if ($workouts->isNotEmpty()) {
        $trainer = User::where('id', $workouts->first()->trainer_id)->first();
    } else {
        $workouts = null;
    }

    return response()->json([
        'user' => [
            'client' => $client,
            'sub' => $sub,
            'sub_status' => $sub->currentStatus(),
            'medical_certificate' => $medical_certificate,
            'medical_certificate_status' => $medical_certificate->currentStatus(),
            'trainer' => $trainer,
            'workouts' => $workouts,
        ]
    ]);
})->middleware('auth:sanctum');

Route::post('/logout', function (Request $request) {
    $request->user()->currentAccessToken()->delete();

    return response()->json([
        'message' => 'Logout effettuato',
    ]);
})->middleware('auth:sanctum');

Route::post('client-login', [ApiController::class, 'client_login']);

Route::post('chat', [ChatController::class, 'chat'])->middleware('auth:sanctum');
Route::delete('chat/{conversationId}', [ChatController::class, 'destroy'])->middleware('auth:sanctum');
Route::get('chat/{conversationId}/history', [ChatController::class, 'history'])->middleware('auth:sanctum');
Route::get('chat/conversations', [ChatController::class, 'index'])->middleware('auth:sanctum');
