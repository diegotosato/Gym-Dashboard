@extends('layouts/default')

@section('title', '- Modifica rinnovo ' . $client->first_name . ' ' . $client->last_name)

@section('content')
    <div class="d-flex gap-2 p-5 pb-4 justify-content-between align-items-end">
        <div class="d-flex gap-2">
            <i class="fa-solid fa-square-pen fs-1 me-2" id="ana-icon"></i>
            <div class="d-flex flex-column">
                <h2 class="mb-1 dash-title fw-bold">{{ $client->first_name }} {{ $client->last_name }}</h2>
                <p class="m-0 dash-text">Modifica abbonamento/rinnovo</p>
            </div>
        </div>

        <a href="{{ route('show-subscriptions', $client) }}" class="btn px-3 back-button">
            <i class="fa-solid fa-arrow-left"></i> Torna alla gestione abbonamento
        </a>
    </div>

    <div class="px-5 pb-5">
        <div class="card border-0 shadow-sm">
            <div class="card-body px-5 py-4">

                <div class="row mb-1">
                    <div class="col-12 mb-2 fw-bold pb-2 border-bottom sector-title">
                        <i class="fa-solid fa-square-pen"></i> Modifica abbonamento/rinnovo
                    </div>
                </div>

                <form action="{{ route('update-subscription', $subscription) }}" method="POST" id="edit-client-form">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-6">
                            <label for="start_date" class="form-label">Data di inizio</label>
                            <input type="date" class="form-control" id="start_date" name="start_date"
                                value="{{ old('start_date', $subscription->start_date->format('Y-m-d')) }}" required>
                        </div>

                        <div class="col-6 mb-3">
                            <label for="sub_type" class="form-label me-4 d-block">Tipo di abbonamento</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="sub_type" id="monthly"
                                    value="monthly"
                                    {{ old('sub_type', $subscription->type) === 'monthly' ? 'checked' : '' }}>
                                <label class="form-check-label" for="monthly">Mensile</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="sub_type" id="quarterly"
                                    value="quarterly"
                                    {{ old('sub_type', $subscription->type) === 'quarterly' ? 'checked' : '' }}>
                                <label class="form-check-label" for="quarterly">4 mesi</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="sub_type" id="annual" value="annual"
                                    {{ old('sub_type', $subscription->type) === 'annual' ? 'checked' : '' }}>
                                <label class="form-check-label" for="annual">Annuale</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4 mb-3 pb-4 border-bottom"></div>

                    <div class="row mb-4">
                        <div class="col-6 d-flex align-items-center">
                            <a href="{{ route('show-subscriptions', $client) }}" class="back-link">
                                <i class="fa-solid fa-arrow-left"></i> Torna indietro
                            </a>
                        </div>

                        <div class="col-6 text-end">
                            <button type="submit" class="btn success-button">
                                <i class="fa-solid fa-check"></i> Registra abbonamento/rinnovo
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
