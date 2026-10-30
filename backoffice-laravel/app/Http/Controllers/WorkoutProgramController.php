<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\WorkoutProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkoutProgramController extends Controller
{
    public function index(Client $client)
    {
        $programs = WorkoutProgram::with('weeks.sessions.exercises')->where('client_id', $client->id)->get();

        return view('workouts/programs/show', compact('client', 'programs'));
    }

    public function store(Request $request, Client $client)
    {
        WorkoutProgram::create([
            'client_id' => $client->id,
            'trainer_id' => Auth::id(),
        ]);

        return redirect()->route('programs', $client)->with('success', 'Programma creato');
    }

    public function destroy(Client $client, WorkoutProgram $program)
    {
        $program->delete();

        return redirect()->route('programs', $client)->with('success', 'Programma cancellato');
    }
}
