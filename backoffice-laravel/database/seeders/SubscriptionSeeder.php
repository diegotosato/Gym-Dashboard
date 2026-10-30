<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Subscription;
use App\Models\Suspension;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class SubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Genera per ogni cliente uno storico di abbonamenti (e a volte sospensioni)
     * plausibile: clienti storici con rinnovi/sospensioni, clienti nuovi,
     * abbonamenti futuri (pending) e clienti che non hanno rinnovato (scaduti).
     */
    public function run(Faker $faker)
    {
        $today = Carbon::today();

        // 35% storici, 30% nuovi, 15% futuri, 20% scaduti/non rinnovati
        $profiles = array_merge(
            array_fill(0, 7, 'veteran'),
            array_fill(0, 6, 'new'),
            array_fill(0, 3, 'future'),
            array_fill(0, 4, 'churned'),
        );

        Client::all()->each(function (Client $client) use ($faker, $profiles, $today) {
            $profile = $faker->randomElement($profiles);
            $type = $faker->randomElement(['monthly', 'monthly', 'monthly', 'quarterly', 'quarterly', 'annual']);

            match ($profile) {
                'future' => $this->seedFuture($client, $type, $faker, $today),
                'churned' => $this->seedChurned($client, $type, $faker, $today),
                'new' => $this->seedOngoing($client, $type, $faker, $today, $faker->numberBetween(5, 270), 0, 20),
                default => $this->seedOngoing($client, $type, $faker, $today, $faker->numberBetween(400, 1100), 45, 25),
            };
        });
    }

    /**
     * Abbonamento con decorrenza futura, cliente non ancora iniziato (status pending).
     */
    private function seedFuture(Client $client, string $type, Faker $faker, Carbon $today): void
    {
        $start = $today->copy()->addDays($faker->numberBetween(3, 45));
        $baseEnd = $this->periodEnd($start, $type);

        Subscription::create([
            'type' => $type,
            'start_date' => $start,
            'base_end_date' => $baseEnd,
            'status' => 'pending',
            'client_id' => $client->id,
        ]);
    }

    /**
     * Cliente che ha rinnovato un po' di volte in passato ma non ha più rinnovato:
     * l'ultimo abbonamento risulta scaduto da tempo.
     */
    private function seedChurned(Client $client, string $type, Faker $faker, Carbon $today): void
    {
        $cursor = $today->copy()->subMonths($faker->numberBetween(12, 30));
        $stopBefore = $today->copy()->subDays($faker->numberBetween(20, 200));

        $subscriptions = [];

        do {
            $end = $this->periodEnd($cursor, $type);

            $subscriptions[] = Subscription::create([
                'type' => $type,
                'start_date' => $cursor,
                'base_end_date' => $end,
                'status' => 'inactive',
                'client_id' => $client->id,
            ]);

            $cursor = $end->copy();
        } while ($end->lt($stopBefore) && count($subscriptions) < 8);

        // qualche sospensione storica, non sull'ultimo rinnovo (già scaduto)
        if (count($subscriptions) > 1 && $faker->boolean(25)) {
            $this->addPastSuspension($faker->randomElement(array_slice($subscriptions, 0, -1)), $faker);
        }
    }

    /**
     * Cliente attivo/in corso: rinnova finché l'ultimo abbonamento copre oggi.
     * Eventualmente aggiunge una sospensione storica e/o una in corso.
     */
    private function seedOngoing(
        Client $client,
        string $type,
        Faker $faker,
        Carbon $today,
        int $firstStartDaysAgo,
        int $historicalSuspensionChance,
        int $currentSuspensionChance
    ): void {
        $cursor = $today->copy()->subDays($firstStartDaysAgo);
        $subscriptions = [];

        do {
            $end = $this->periodEnd($cursor, $type);

            $subscriptions[] = Subscription::create([
                'type' => $type,
                'start_date' => $cursor,
                'base_end_date' => $end,
                'status' => $cursor->isFuture() ? 'pending' : 'active',
                'client_id' => $client->id,
            ]);

            $cursor = $end->copy();
        } while ($end->lt($today) && count($subscriptions) < 10);

        $lastSubscription = end($subscriptions);

        if (count($subscriptions) > 1 && $faker->boolean($historicalSuspensionChance)) {
            $this->addPastSuspension($faker->randomElement(array_slice($subscriptions, 0, -1)), $faker);
        }

        if ($faker->boolean($currentSuspensionChance)) {
            $this->addCurrentSuspension($lastSubscription, $faker, $today);
        }
    }

    /**
     * Sospensione già conclusa in passato, dentro la finestra di un abbonamento passato.
     */
    private function addPastSuspension(Subscription $subscription, Faker $faker): void
    {
        $start = Carbon::parse($subscription->getRawOriginal('start_date'));
        $end = Carbon::parse($subscription->getRawOriginal('base_end_date'));

        $windowDays = $start->diffInDays($end);
        if ($windowDays < 5) {
            return;
        }

        $susStart = $start->copy()->addDays($faker->numberBetween(1, max(1, $windowDays - 4)));
        $susEnd = $susStart->copy()->addDays($faker->numberBetween(3, 15));

        Suspension::create([
            'subscription_id' => $subscription->id,
            'start_date' => $susStart,
            'end_date' => $susEnd,
        ]);

        $subscription->base_end_date = $end->addDays($susStart->diffInDays($susEnd));
        $subscription->save();
    }

    /**
     * Sospensione sull'abbonamento corrente: può essere già conclusa, in corso
     * con data fine futura, oppure a tempo indeterminato (end_date null).
     */
    private function addCurrentSuspension(Subscription $subscription, Faker $faker, Carbon $today): void
    {
        $start = Carbon::parse($subscription->getRawOriginal('start_date'));
        $baseEnd = Carbon::parse($subscription->getRawOriginal('base_end_date'));

        $windowStart = $start->max($today->copy()->subDays(20));
        if ($windowStart->gte($today) || $windowStart->gte($baseEnd)) {
            return;
        }

        $susStart = Carbon::instance($faker->dateTimeBetween($windowStart, $today->copy()->subDay()));

        $kind = $faker->randomElement(['past', 'future', 'indefinite']);

        $susEnd = match ($kind) {
            'past' => $susStart->copy()->addDays($faker->numberBetween(2, min(10, max(2, $susStart->diffInDays($today) - 1)))),
            'future' => $today->copy()->addDays($faker->numberBetween(2, 15)),
            'indefinite' => null,
        };

        // "past" deve comunque essere già conclusa prima di oggi
        if ($kind === 'past' && $susEnd->gte($today)) {
            $susEnd = $today->copy()->subDay();
        }

        Suspension::create([
            'subscription_id' => $subscription->id,
            'start_date' => $susStart,
            'end_date' => $susEnd,
        ]);

        $addedDays = $susStart->diffInDays($susEnd ?? $today);

        $subscription->base_end_date = $baseEnd->addDays($addedDays);
        $subscription->status = 'suspended';
        $subscription->save();
    }

    private function periodEnd(Carbon $start, string $type): Carbon
    {
        return match ($type) {
            'monthly' => $start->copy()->addMonth(),
            'quarterly' => $start->copy()->addMonths(4),
            default => $start->copy()->addYear(),
        };
    }
}
