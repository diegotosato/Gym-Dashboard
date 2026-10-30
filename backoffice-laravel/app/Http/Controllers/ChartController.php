<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Subscription;
use App\Models\Suspension;
use Carbon\Carbon;
use IcehouseVentures\LaravelChartjs\Facades\Chartjs;

class ChartController extends Controller
{
    public function index()
    {
        $males = Client::where('gender', 'male')->get();
        $females = Client::where('gender', 'female')->get();

        $genders = Chartjs::build()
            ->name('pieGenders')
            ->type('doughnut')
            ->labels(['Uomini', 'Donne'])
            ->datasets([
                [
                    'backgroundColor' => ['#0d43b9', '#e60e8c'],
                    'hoverBackgroundColor' => ['#0b399e', '#c90c7a'],
                    'data' => [count($males), count($females)],
                    'hoverOffset' => 10,
                ],
            ])
            ->options([
                'responsive' => true,
                'plugins' => [
                    'legend' => [
                        'position' => 'bottom',
                        'labels' => [
                            'color' => '#333',
                            'font' => [
                                'size' => 14,
                            ],
                            'usePointStyle' => true,
                            'pointStyle' => 'circle',
                        ],
                    ],
                    'tooltip' => [
                        'enabled' => true,
                    ],
                ],
                'cutout' => '60%', // dimensione del buco centrale
            ]);

        $clients = Client::all();
        $clients_number = count($clients);
        $subs = [];
        $suspensions = [];

        foreach ($clients as $client) {
            $last_sub = Subscription::where('client_id', $client->id)->orderBy('start_date', 'desc')->first();

            if (!$last_sub) {
                continue;
            }

            if ($last_sub->currentStatus() === 'suspended') {
                $suspensions[] = $last_sub;
            } else {
                $subs[] = $last_sub;
            }
        }

        // dd($suspensions);
        $active = [];
        $inactive = [];
        $pending = [];

        // active
        foreach ($subs as $sub) {
            if ($sub->start_date < Carbon::today() && $sub->base_end_date > Carbon::today()) {
                $active[] = $sub;
            }
        }

        // inactive
        foreach ($subs as $sub) {
            if ($sub->base_end_date < Carbon::today()) {
                $inactive[] = $sub;
            }
        }

        // pending
        foreach ($subs as $sub) {
            if ($sub->start_date > Carbon::today()) {
                $pending[] = $sub;
            }
        }

        $sub_status = Chartjs::build()
            ->name('pieSubsStatus')
            ->type('doughnut')
            ->labels(['Abbonamenti attivi', 'Abbonamenti scaduti', 'Abbonamenti futuri', 'Abbonamenti sospesi'])
            ->datasets([
                [
                    'backgroundColor' => ['#198754', '#dc3545', '#0dcaf0', '#ffc107'],
                    'hoverBackgroundColor' => ['#16774a', '#bd2c3b', '#0eabca', '#d8a306'],
                    'data' => [count($active), count($inactive), count($pending), count($suspensions)],
                    'hoverOffset' => 10,
                ],
            ])
            ->options([
                'responsive' => true,
                'plugins' => [
                    'legend' => [
                        'position' => 'bottom',
                        'labels' => [
                            'color' => '#333',
                            'font' => [
                                'size' => 14,
                            ],
                            'usePointStyle' => true,
                            'pointStyle' => 'circle',
                        ],
                    ],
                    'tooltip' => [
                        'enabled' => true,
                    ],
                ],
                'cutout' => '60%', // dimensione del buco centrale
            ]);

        $currentYear = Carbon::now()->year;
        $renewalsByMonth = Subscription::query()
            ->whereYear('start_date', $currentYear)
            ->get(['start_date'])
            ->countBy(fn(Subscription $subscription): int => $subscription->start_date->month);

        $months_renewals = Chartjs::build()
            ->name('pieMonthsRenewals')
            ->type('line')
            ->labels([
                'Gennaio',
                'Febbraio',
                'Marzo',
                'Aprile',
                'Maggio',
                'Giugno',
                'Luglio',
                'Agosto',
                'Settembre',
                'Ottobre',
                'Novembre',
                'Dicembre',
            ])
            ->datasets([
                [
                    'label' => "Iscrizioni/Rinnovi {$currentYear}",
                    'borderColor' => '#0d6efd',
                    'backgroundColor' => 'rgba(13, 110, 253, 0.2)',
                    'data' => collect(range(1, 12))
                        ->map(fn(int $month): int => $renewalsByMonth->get($month, 0))
                        ->all(),
                ],
            ])
            ->options([
                'responsive' => true,
                'maintainAspectRatio' => false,
                'plugins' => [
                    'legend' => [
                        'position' => 'bottom',
                        'labels' => [
                            'color' => '#333',
                            'font' => [
                                'size' => 14,
                            ],
                            'usePointStyle' => true,
                            'pointStyle' => 'circle',
                        ],
                    ],
                    'tooltip' => [
                        'enabled' => true,
                    ],
                ],
                'scales' => [
                    'y' => [
                        'beginAtZero' => true,
                        'max' => 30,
                        'ticks' => [
                            'stepSize' => 5,
                            'autoSkip' => false,
                            'font' => [
                                'size' => 14,
                            ],
                        ]
                    ],
                ],
            ]);

        return view('dashboard', compact('genders', 'sub_status', 'months_renewals', 'clients_number'));
    }
}
