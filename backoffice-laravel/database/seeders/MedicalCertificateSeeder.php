<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\MedicalCertificate;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class MedicalCertificateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Genera per ogni cliente uno storico plausibile di certificati medici
     * (validità di 1 anno): clienti senza certificato, con certificato
     * attivo, con storico di rinnovi e con l'ultimo certificato scaduto.
     */
    public function run(Faker $faker)
    {
        $today = Carbon::today();

        // 10% senza certificato, 45% attivo (con o senza storico), 20% con storico attivo, 25% scaduto
        $profiles = array_merge(
            array_fill(0, 2, 'missing'),
            array_fill(0, 5, 'active'),
            array_fill(0, 4, 'active_with_history'),
            array_fill(0, 5, 'expired'),
        );

        Client::all()->each(function (Client $client) use ($faker, $profiles, $today) {
            $profile = $faker->randomElement($profiles);

            match ($profile) {
                'missing' => null,
                'active' => $this->seedActive($client, $faker, $today),
                'active_with_history' => $this->seedActiveWithHistory($client, $faker, $today),
                'expired' => $this->seedExpired($client, $faker, $today),
            };
        });
    }

    /**
     * Un solo certificato in corso di validità.
     */
    private function seedActive(Client $client, Faker $faker, Carbon $today): void
    {
        $start = $today->copy()->subDays($faker->numberBetween(5, 300));

        $this->createCertificate($client, $start);
    }

    /**
     * Certificato attuale valido preceduto da uno o più rinnovi passati.
     */
    private function seedActiveWithHistory(Client $client, Faker $faker, Carbon $today): void
    {
        $renewals = $faker->numberBetween(1, 3);
        $start = $today->copy()->subYears($renewals + 1)->addDays($faker->numberBetween(0, 30));

        for ($i = 0; $i <= $renewals; $i++) {
            $this->createCertificate($client, $start);
            $start = $start->copy()->addYear();
        }
    }

    /**
     * Ultimo certificato scaduto e mai rinnovato.
     */
    private function seedExpired(Client $client, Faker $faker, Carbon $today): void
    {
        $start = $today->copy()->subDays($faker->numberBetween(370, 730));

        $this->createCertificate($client, $start);
    }

    private function createCertificate(Client $client, Carbon $start): MedicalCertificate
    {
        return MedicalCertificate::create([
            'client_id' => $client->id,
            'medical_certificate_start_date' => $start,
            'medical_certificate_expiry_date' => $start->copy()->addYear(),
        ]);
    }
}
