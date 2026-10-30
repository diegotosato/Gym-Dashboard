<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Subscription;
use App\Models\Suspension;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class SuspensionController extends Controller
{
    public function store(Request $request, Client $client, Subscription $lastSub)
    {
        $request->validate([
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date'],
        ]);

        $suspension = Suspension::create([
            'subscription_id' => $lastSub->id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        $start = Carbon::parse($request->start_date);
        $end = Carbon::parse($request->end_date);

        $diffInDays = $start->diffInDays($end);

        $lastSub->base_end_date = Carbon::parse($lastSub->getRawOriginal('base_end_date'))->addDays($diffInDays);
        $lastSub->save();

        return redirect()->route('show-subscriptions', $client)->with('success', 'Sospensione registrata');
    }

    /**
     * Termina anticipatamente una sospensione in corso (o futura), riportando la
     * subscription ad avere solo i giorni di sospensione effettivamente usati.
     */
    public function update(Suspension $suspension)
    {
        $subscription = $suspension->subscription;
        $today = Carbon::today();

        $start = Carbon::parse($suspension->getRawOriginal('start_date'));

        // sospensione non ancora iniziata: terminarla equivale ad annullarla del tutto
        if ($start->gte($today)) {
            return $this->destroy($suspension);
        }

        $oldEnd = $suspension->getRawOriginal('end_date')
            ? Carbon::parse($suspension->getRawOriginal('end_date'))
            : $today;

        if ($today->gte($oldEnd)) {
            return redirect()->route('show-subscriptions', $subscription->client)
                ->with('success', 'La sospensione è già terminata');
        }

        // finisce ieri, cosi da oggi il cliente non risulta più sospeso (isSuspended usa end_date >= oggi)
        $newEnd = $today->copy()->subDay();

        $oldDays = $start->diffInDays($oldEnd);
        $newDays = $start->diffInDays($newEnd);
        $daysToRemove = $oldDays - $newDays;

        $subscription->base_end_date = Carbon::parse($subscription->getRawOriginal('base_end_date'))->subDays($daysToRemove);
        $subscription->save();

        $suspension->end_date = $newEnd;
        $suspension->save();

        return redirect()->route('show-subscriptions', $subscription->client)->with('success', 'Sospensione terminata');
    }

    /**
     * Annulla del tutto una sospensione (es. inserita per errore), rimuovendo
     * tutti i giorni che aveva aggiunto alla subscription.
     */
    public function destroy(Suspension $suspension)
    {
        $subscription = $suspension->subscription;

        $start = Carbon::parse($suspension->getRawOriginal('start_date'));
        $end = $suspension->getRawOriginal('end_date')
            ? Carbon::parse($suspension->getRawOriginal('end_date'))
            : Carbon::today();

        $days = $start->diffInDays($end);

        $subscription->base_end_date = Carbon::parse($subscription->getRawOriginal('base_end_date'))->subDays($days);
        $subscription->save();

        $suspension->delete();

        return redirect()->route('show-subscriptions', $subscription->client)->with('success', 'Sospensione eliminata');
    }
}
