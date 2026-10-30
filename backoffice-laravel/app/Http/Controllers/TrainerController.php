<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class TrainerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $clients = Client::query()
            ->when($request->search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhereRaw("CONCAT(first_name, ' ', last_name) like ?", ["%{$search}%"]);
                });
            })
            ->with(['role', 'subscriptions.suspensions', 'medical_certificates'])
            ->paginate(10)
            ->withQueryString();

        return view('trainer/trainerDash', compact('clients'));
    }

    public function create(Client $client)
    {
        return view('trainer/create-program', compact('client'));
    }

    public function show(Client $client)
    {
        return view('trainer/show-client', compact('client'));
    }

    public function createWorkout(Client $client)
    {
        return view('workouts-views/create', compact('client'));
    }

    public function storeWorkout(Request $request, Client $client)
    {
        dd($request);

        return view('workouts-views/create', compact('client'));
    }
}
