<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\WorkoutProgram;
use App\Models\WorkoutWeek;

class WorkoutWeekController extends Controller
{
    public function index(WorkoutProgram $program)
    {
        $weeks = WorkoutWeek::where('workout_program_id', $program->id)
            ->with('sessions')
            ->get();
        $client = Client::where('id', $program->client_id)->get();

        return view('workouts/weeks/show', compact('program', 'weeks', 'client'));
    }

    public function store(WorkoutProgram $program)
    {
        // dd($program);

        $lastWeek = WorkoutWeek::where('workout_program_id', $program->id)->get()->last();

        if (is_null($lastWeek)) {
            WorkoutWeek::create([
                'workout_program_id' => $program->id,
                'week_number' => 1,
            ]);

            return redirect()->route('weeks', $program)->with('success', 'Settimana creata');
        }

        WorkoutWeek::create([
            'workout_program_id' => $program->id,
            'week_number' => $lastWeek->week_number + 1,
        ]);

        return redirect()->route('weeks', $program)->with('success', 'Settimana creata');
    }

    public function destroy(WorkoutProgram $program, WorkoutWeek $week)
    {
        $week->delete();

        return redirect()->route('weeks', $program)->with('success', 'Settimana eliminata');
    }
}
