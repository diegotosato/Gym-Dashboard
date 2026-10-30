@extends('layouts/default')

@section('title', 'Nuovo cliente')

@section('content')
    <div class="d-flex gap-2 p-5 pb-4 justify-content-between align-items-end">
        <div class="d-flex gap-2">
            <i class="fa-solid fa-address-card fs-1 me-2" id="ana-icon"></i>
            <div class="d-flex flex-column">
                <h2 class="mb-1 dash-title fw-bold">Nuovo cliente</h2>
                <p class="m-0 dash-text">Registra un nuovo cliente in palestra</p>
            </div>
        </div>

        <a href="{{ route('index-client') }}" class="btn px-3 back-button">
            <i class="fa-solid fa-arrow-left"></i> Torna alla lista
        </a>
    </div>

    <div class="px-5 pb-5">
        <div class="card border-0 shadow-sm">
            <div class="card-body px-5 py-4">
                <form action="{{ route('store-client') }}" method="POST" id="add-client-form">
                    @csrf

                    <div class="row mb-4">
                        <div class="col-12 mb-2 fw-bold pb-2 border-bottom sector-title">
                            <i class="fa-solid fa-user me-2"></i> Dati anagrafici
                        </div>

                        {{-- nome/cognome --}}
                        <div class="col-6 mb-2">
                            <label for="first_name" class="form-label fs-6">Nome</label>
                            <input type="text" class="form-control" id="first_name" name="first_name"
                                value="{{ old('first_name') }}" placeholder="Mario" required>
                        </div>

                        <div class="col-6 mb-2">
                            <label for="last_name" class="form-label fs-6">Cognome</label>
                            <input type="text" class="form-control" id="last_name" name="last_name"
                                value="{{ old('last_name') }}" placeholder="Rossi" required>
                        </div>

                        {{-- gender/email/phone --}}
                        <div class="col-4 mb-2">
                            <label for="gender" class="form-label fs-6 me-4 d-block">Sesso</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" id="male" value="male"
                                    {{ old('gender') === 'male' ? 'checked' : '' }}>
                                <label class="form-check-label" for="male">Maschio</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" id="female" value="female"
                                    {{ old('gender') === 'female' ? 'checked' : '' }}>
                                <label class="form-check-label" for="female">Femmina</label>
                            </div>
                        </div>

                        <div class="col-4 mb-2">
                            <label for="email" class="form-label fs-6">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                value="{{ old('email') }}" placeholder="mario@email.com" required>
                        </div>

                        <div class="col-4 mb-2">
                            <label for="phone" class="form-label fs-6">Telefono</label>
                            <input type="text" class="form-control" id="phone" name="phone"
                                value="{{ old('phone') }}" placeholder="1234567890" required>
                        </div>

                        {{-- birth/address --}}
                        <div class="col-3">
                            <label for="date_of_birth" class="form-label fs-6">Data di nascita</label>
                            <input type="date" class="form-control" id="date_of_birth" name="date_of_birth"
                                value="{{ old('date_of_birth') }}" required>
                        </div>

                        {{-- tax_code --}}
                        <div class="col-3">
                            <label for="tax_code" class="form-label fs-6">Codice Fiscale</label>
                            <input type="text" class="form-control text-uppercase" id="tax_code" name="tax_code"
                                value="{{ old('tax_code') }}" placeholder="AAABBB11A11B123A" required>
                        </div>

                        <div class="col-6 mb-2">
                            <label for="address" class="form-label fs-6">Indirizzo</label>
                            <input type="text" class="form-control" id="address" name="address"
                                value="{{ old('address') }}" placeholder="Via Aldo Rossi, 1" required>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-12 mb-2 fw-bold pb-2 border-bottom sector-title">
                            <i class="fa-solid fa-shield-halved"></i> Certificato medico
                        </div>

                        {{-- certificato medico --}}
                        <div class="col-6 mb-2">
                            <label for="medical_certificate_start_date" class="form-label fs-6">Data di effettuazione
                                certificato medico</label>
                            <input type="date" class="form-control" id="medical_certificate_start_date"
                                name="medical_certificate_start_date" value="{{ old('medical_certificate_start_date') }}"
                                required>
                        </div>

                        <div class="col-6 mb-2">
                            <label for="medical_certificate_expiry_date" class="form-label fs-6">Data di scadenza
                                certificato
                                medico</label>
                            <input type="date" class="form-control" id="medical_certificate_expiry_date"
                                name="medical_certificate_expiry_date"
                                value="{{ old('medical_certificate_expiry_date') }}" required>
                        </div>
                    </div>

                    {{-- status abbonamento --}}
                    <div class="row mb-4">
                        <div class="col-12 mb-2 fw-bold pb-2 border-bottom sector-title">
                            <i class="fa-regular fa-id-card"></i> Abbonamento
                        </div>

                        <div class="col-6 mb-2">
                            <label for="sub_type" class="form-label fs-6 me-4 d-block">Tipo di abbonamento</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="sub_type" id="monthly"
                                    value="monthly" {{ old('sub_type') === 'monthly' ? 'checked' : '' }}>
                                <label class="form-check-label" for="monthly">Mensile</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="sub_type" id="quarterly"
                                    value="quarterly" {{ old('sub_type') === 'quarterly' ? 'checked' : '' }}>
                                <label class="form-check-label" for="quarterly">4 mesi</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="sub_type" id="annual"
                                    value="annual" {{ old('sub_type') === 'annual' ? 'checked' : '' }}>
                                <label class="form-check-label" for="annual">Annuale</label>
                            </div>
                        </div>

                        <div class="col-6 mb-2">
                            <label for="sub_start_date" class="form-label fs-6">Data inizio abbonamento</label>
                            <input type="date" class="form-control" id="sub_start_date" name="sub_start_date"
                                value="{{ old('sub_start_date') }}" required>
                        </div>
                    </div>

                    {{-- notes --}}
                    <div class="row mb-3 pb-5 border-bottom">
                        <div class="col-12 mb-2 fw-bold pb-2 border-bottom sector-title">
                            <i class="fa-regular fa-note-sticky"></i> Note
                        </div>

                        <div class="col-12">
                            <textarea class="form-control" name="notes" id="notes" rows="2" style="resize: none"
                                placeholder="Scrivi eventuali note">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6 d-flex align-items-center">
                            <a href="{{ route('index-client') }}" class="back-link">
                                <i class="fa-solid fa-arrow-left"></i> Torna indietro
                            </a>
                        </div>

                        <div class="col-6 text-end">
                            <button type="submit" class="btn success-button">
                                <i class="fa-solid fa-check"></i> Registra cliente
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
