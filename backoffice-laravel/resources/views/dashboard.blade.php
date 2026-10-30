@extends('layouts/default')

@section('title', '- Dashboard')

@section('content')
    <div class="d-flex gap-2 p-5">
        <i class="fa-solid fa-chart-simple fs-1 me-2" id="ana-icon"></i>
        <div class="d-flex flex-column">
            <h2 class="mb-1 dash-title fw-bold">Analytics</h2>
            <p class="m-0 dash-text">Panoramica generale della tua palestra</p>
        </div>
    </div>

    <div class="px-5 analytics-grid">
        <div class="card an-card border-0 shadow pt-3 px-3 pb-5">
            <div class="card-h d-flex align-items-center gap-3 mb-3">
                <div class="card-logo fs-4">
                    <i class="fa-solid fa-people-group"></i>
                </div>

                <p class="m-0 fs-4 fw-bold">Distribuzione clienti ({{ $clients_number }})</p>
            </div>

            <div class="chart-container">
                <x-chartjs-component :chart="$genders" />
            </div>
        </div>

        <div class="card an-card border-0 shadow pt-3 px-3 pb-5">
            <div class="card-h d-flex align-items-center gap-3 mb-3">
                <div class="card-logo fs-4">
                    <i class="fa-solid fa-chart-line"></i>
                </div>

                <p class="m-0 fs-4 fw-bold">Stato abbonamenti</p>
            </div>

            <div class="chart-container">
                <x-chartjs-component :chart="$sub_status" />
            </div>
        </div>

        <div class="card an-card border-0 shadow pt-3 px-3 pb-5">
            <div class="card-h d-flex align-items-center gap-3 mb-3">
                <div class="card-logo fs-4">
                    <i class="fa-regular fa-calendar"></i>
                </div>

                <p class="m-0 fs-4 fw-bold">Iscrizioni/Rinnovi</p>
            </div>

            <div class="chart-container">
                <x-chartjs-component :chart="$months_renewals" />
            </div>
        </div>
    </div>
@endsection
