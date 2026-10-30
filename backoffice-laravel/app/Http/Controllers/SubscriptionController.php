<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Subscription;
use App\Models\Suspension;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Client $client)
    {
        // dd($client);

        $request->validate([
            'start_date' => ['required', 'date'],
            'sub_type' => ['required', 'string', 'in:monthly,quarterly,annual'],
        ], [
            'start_date.required' => 'La data di inizio è obbligatoria',
            'sub_type.required' => 'La tipologia è obbligatoria',
        ]);

        $sub_status = null;
        $start = Carbon::parse($request->start_date);

        $sub_status = $start->isFuture() ? 'pending' : 'active';

        if ($request->sub_type === 'monthly') {
            $base_end_date = $start->copy()->addMonth();
        } elseif ($request->sub_type === 'quarterly') {
            $base_end_date = $start->copy()->addMonths(4);
        } else {
            $base_end_date = $start->copy()->addYear();
        }

        $sub = Subscription::create([
            'type' => $request->sub_type,
            'start_date' => $start,
            'base_end_date' => $base_end_date,
            'status' => $sub_status,
            'client_id' => $client->id,
        ]);

        return redirect()->route('show-subscriptions', $client)->with('success', 'Nuovo rinnovo effettuato');
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        $subs = Subscription::where('client_id', $client->id)
            ->with('suspensions')
            ->orderBy('start_date', 'asc')
            ->get();

        $lastSub = $subs->last();

        $suspensions = [];
        foreach ($subs as $sub) {
            $sus = Suspension::where('subscription_id', $sub->id)->get();
            if (count($sus) > 0) {
                $suspensions[] = $sus;
            }
        }

        return view('subs-views/edit', compact('client', 'subs', 'lastSub', 'suspensions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subscription $subscription)
    {
        $client = $subscription->client;

        return view('subs-views/edit-subscription', compact('client', 'subscription'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subscription $subscription)
    {
        $request->validate([
            'start_date' => ['required', 'date'],
            'sub_type' => ['required', 'string', 'in:monthly,quarterly,annual'],
        ], [
            'start_date.required' => 'La data di inizio è obbligatoria',
            'sub_type.required' => 'La tipologia è obbligatoria',
        ]);

        $start = Carbon::parse($request->start_date);

        if ($request->sub_type === 'monthly') {
            $base_end_date = $start->copy()->addMonth();
        } elseif ($request->sub_type === 'quarterly') {
            $base_end_date = $start->copy()->addMonths(4);
        } else {
            $base_end_date = $start->copy()->addYear();
        }

        // riapplica i giorni delle sospensioni già registrate, altrimenti verrebbero persi
        $base_end_date->addDays($this->suspensionDays($subscription));

        $subscription->type = $request->sub_type;
        $subscription->start_date = $start;
        $subscription->base_end_date = $base_end_date;
        $subscription->status = $start->isFuture() ? 'pending' : 'active';
        $subscription->save();

        return redirect()->route('show-subscriptions', $subscription->client)->with('success', 'Rinnovo aggiornato');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subscription $subscription)
    {
        $client = $subscription->client;

        $subscription->delete();

        return redirect()->route('show-subscriptions', $client)->with('success', 'Rinnovo eliminato');
    }

    /**
     * Somma dei giorni coperti dalle sospensioni di una subscription (in corso = fino a oggi).
     */
    private function suspensionDays(Subscription $subscription): int
    {
        $today = Carbon::today();

        return $subscription->suspensions->sum(function (Suspension $suspension) use ($today) {
            $start = Carbon::parse($suspension->getRawOriginal('start_date'));
            $end = $suspension->getRawOriginal('end_date')
                ? Carbon::parse($suspension->getRawOriginal('end_date'))
                : $today;

            return $start->diffInDays($end);
        });
    }
}
