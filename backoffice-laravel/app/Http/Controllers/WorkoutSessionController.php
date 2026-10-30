<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\WorkoutProgram;
use App\Models\WorkoutSession;
use App\Models\WorkoutWeek;

class WorkoutSessionController extends Controller
{
    public function index(WorkoutWeek $week)
    {
        $sessions = WorkoutSession::where('workout_week_id', $week->id)->get();
        $program = WorkoutProgram::where('id', $week->workout_program_id)->get();
        $client_array = Client::where('id', $program[0]->client_id)->get();
        $client = $client_array[0];

        return view('workouts/sessions/show', compact('week', 'sessions', 'program', 'client'));
    }

    public function store(WorkoutWeek $week)
    {
        WorkoutSession::create([
            'workout_week_id' => $week->id,
        ]);

        return redirect()->route('sessions', $week)->with('success', 'Nuovo allenamento aggiunto');
    }

    public function destroy(WorkoutWeek $week, WorkoutSession $session)
    {
        $session->delete();

        return redirect()->route('sessions', $week)->with('success', 'Allenamento eliminato');
    }
}
