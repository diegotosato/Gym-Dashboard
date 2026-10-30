@extends('layouts/default')

@section('title', '- ' . $client->first_name . ' ' . $client->last_name . ' - Modifica certificato medico')

@section('content')
    <div class="d-flex gap-2 p-5 pb-4 justify-content-between align-items-end">
        <div class="d-flex gap-2">
            <i class="fa-solid fa-square-pen fs-1 me-2" id="ana-icon"></i>
            <div class="d-flex flex-column">
                <h2 class="mb-1 dash-title fw-bold">{{ $client->first_name }} {{ $client->last_name }}</h2>
                <p class="m-0 dash-text">Modifica certificato medico</p>
            </div>
        </div>

        <a href="{{ route('show-medical-cert', $client) }}" class="btn px-3 back-button">
            <i class="fa-solid fa-arrow-left"></i> Torna ai certificati cliente
        </a>
    </div>

    <div class="px-5 pb-5">
        <div class="card border-0 shadow-sm">
            <div class="card-body px-5 py-4">

                <div class="row mb-1">
                    <div class="col-12 mb-2 fw-bold pb-2 border-bottom sector-title">
                        <i class="fa-solid fa-square-pen"></i> Modifica certificato medico
                    </div>
                </div>

                <form action="{{ route('update-medical-cert', $medical_certificate) }}" method="POST"
                    id="edit-client-form">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-6">
                            <label for="medical_certificate_start_date" class="form-label">Data emissione</label>
                            <input type="date" class="form-control" id="medical_certificate_start_date"
                                name="medical_certificate_start_date"
                                value="{{ old('medical_certificate_start_date', $medical_certificate->medical_certificate_start_date->format('Y-m-d')) }}"
                                required>
                        </div>

                        <div class="col-6">
                            <label for="medical_certificate_expiry_date" class="form-label">Data fine validità</label>
                            <input type="date" class="form-control" id="medical_certificate_expiry_date"
                                name="medical_certificate_expiry_date"
                                value="{{ old('medical_certificate_expiry_date', $medical_certificate->medical_certificate_expiry_date->format('Y-m-d')) }}"
                                required>
                        </div>
                    </div>

                    <div class="row mt-4 mb-3 pb-4 border-bottom"></div>

                    <div class="row mb-4">
                        <div class="col-6 d-flex align-items-center">
                            <a href="{{ route('show-medical-cert', $client) }}" class="back-link">
                                <i class="fa-solid fa-arrow-left"></i> Torna indietro
                            </a>
                        </div>

                        <div class="col-6 text-end">
                            <button type="submit" class="btn success-button">
                                <i class="fa-solid fa-check"></i> Registra modifiche C.M.
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
