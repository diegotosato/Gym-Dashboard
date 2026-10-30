<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\WorkoutExercise;
use App\Models\WorkoutProgram;
use App\Models\WorkoutSession;
use App\Models\WorkoutWeek;
use Illuminate\Http\Request;

class WorkoutExerciseController extends Controller
{
    public function index(WorkoutSession $session)
    {
        $exercises = WorkoutExercise::where('workout_session_id', $session->id)->get();
        $week = WorkoutWeek::where('id', $session->workout_week_id)->get();
        $program = WorkoutProgram::where('id', $week[0]->workout_program_id)->get();
        $client_array = Client::where('id', $program[0]->client_id)->get();
        $client = $client_array[0];

        return view('workouts/exercises/show', compact('exercises', 'session', 'week', 'client'));
    }

    public function store(WorkoutSession $session, Request $request)
    {
        $request->validate([
            'exercise_name' => ['required', 'string'],
        ], [
            'exercise_name.required' => "Nome dell'esercizio necessario",
        ]);

        WorkoutExercise::create([
            'workout_session_id' => $session->id,
            'exercise_name' => ucfirst($request->exercise_name),
            'sets' => $request->sets,
            'reps' => $request->reps,
            'weight' => $request->weight,
            'notes' => $request->notes,
        ]);

        return redirect()->route('exercises', $session)->with('success', 'Esercizio aggiunto');
    }

    public function edit(WorkoutSession $session, WorkoutExercise $exercise)
    {
        $getEx = WorkoutExercise::where('exercise_name', $exercise->exercise_name)->get();
        $week = WorkoutWeek::where('id', $session->workout_week_id)->get();
        $program = WorkoutProgram::where('id', $week[0]->workout_program_id)->get();
        $client_array = Client::where('id', $program[0]->client_id)->get();

        $exercise = $getEx[0];
        $client = $client_array[0];

        return view('workouts/exercises/edit', compact('exercise', 'session', 'client'));
    }

    public function update(Request $request, WorkoutSession $session, WorkoutExercise $exercise)
    {
        $request->validate([
            'exercise_name' => ['required', 'string'],
        ], [
            'exercise_name.required' => "Nome dell'esercizio necessario",
        ]);

        $exercise->exercise_name = $request->exercise_name;
        $exercise->sets = $request->sets;
        $exercise->reps = $request->reps;
        $exercise->weight = $request->weight;
        $exercise->notes = $request->notes;
        $exercise->save();

        return redirect()->route('exercises', $session)->with('success', 'Esercizio mofificato');
    }

    public function destroy(WorkoutSession $session, WorkoutExercise $exercise)
    {
        $exercise->delete();

        return redirect()->route('exercises', $session)->with('success', 'Esercizio eliminato');
    }
}
