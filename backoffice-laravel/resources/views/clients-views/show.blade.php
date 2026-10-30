@extends('layouts/default')

@section('title', "- $client->first_name $client->last_name")

@section('content')
    <div class="d-flex gap-2 p-5 justify-content-between align-items-end">
        <div class="d-flex gap-2">
            <i class="fa-solid fa-clipboard-user fs-1 me-2" id="ana-icon"></i>
            <div class="d-flex flex-column">
                <h2 class="mb-1 dash-title fw-bold">{{ $client->first_name }} {{ $client->last_name }}</h2>
                <p class="m-0 dash-text">Infografica cliente</p>
            </div>
        </div>

        <a href="{{ route('index-client') }}" class="btn px-3 back-button">
            <i class="fa-solid fa-arrow-left"></i> Torna alla lista
        </a>
    </div>

    <div class="px-5 pb-5">
        <div class="card border-0 shadow-sm">
            <div class="card-body px-5 py-4">

                <div class="row mb-4">
                    <div class="col-12 mb-2 fw-bold pb-2 border-bottom sector-title">
                        <i class="fa-solid fa-user me-2"></i> Dati anagrafici
                    </div>

                    {{-- nome/cognome --}}
                    <div class="col-3 mb-3">
                        <label class="form-label fs-6 d-block m-0">Nome e cognome</label>
                        <b>
                            {{ $client->first_name }} {{ $client->last_name }}
                        </b>
                    </div>

                    {{-- email --}}
                    <div class="col-3">
                        <label class="form-label fs-6 d-block m-0">Email</label>
                        <b>
                            {{ $client->email }}
                        </b>
                    </div>

                    {{-- telefono --}}
                    <div class="col-3">
                        <label class="form-label fs-6 d-block m-0">Telefono</label>
                        <b>
                            {{ $client->phone }}
                        </b>
                    </div>

                    {{-- invio credenziali per mail --}}
                    <div class="col-3">
                        <form action="{{ route('new-credentials', $client) }}" method="POST">
                            {{-- @csrf --}}

                            <button type="submit" class="btn send-button">
                                <i class="fa-solid fa-paper-plane"></i> Invia credenziali per APP
                            </button>
                        </form>
                    </div>

                    {{-- data nascita --}}
                    <div class="col-3 mb-3">
                        <label class="form-label fs-6 d-block m-0">Data di nascita</label>
                        <b>
                            {{ $client->date_of_birth->format('d-m-Y') }}
                        </b>
                    </div>

                    {{-- indirizzo --}}
                    <div class="col-3">
                        <label class="form-label fs-6 d-block m-0">Indirizzo</label>
                        <b>
                            {{ $client->address }}
                        </b>
                    </div>

                    {{-- codice fiscale --}}
                    <div class="col-3">
                        <label class="form-label fs-6 d-block m-0">Codice fiscale</label>
                        <b>
                            {{ $client->tax_code }}
                        </b>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-12 mb-2 fw-bold pb-2 border-bottom sector-title">
                        <i class="fa-solid fa-shield-halved"></i> decorrenza Certificato medico
                    </div>

                    {{-- certificato medico --}}
                    <div class="col-4 mb-3">
                        <label class="form-label fs-6 d-block m-0">Data emissione certificato medico</label>
                        <b>
                            @if ($medical_certificates->last())
                                {{ $medical_certificates->last()->medical_certificate_start_date->format('d/m/Y') }}
                            @endif
                        </b>
                    </div>

                    <div class="col-4">
                        <label class="form-label fs-6 d-block m-0">Data scadenza certificato medico</label>
                        @if ($medical_certificates->last())
                            @if ($medical_certificates->last()->medical_certificate_expiry_date >= new DateTime('today'))
                                <b style="color: var(--color-success)">
                                    C.M. valido fino al
                                    {{ $medical_certificates->last()->medical_certificate_expiry_date->format('d/m/Y') }}
                                </b>
                            @else
                                <b style="color: var(--color-danger)">
                                    C.M. scaduto il
                                    {{ $medical_certificates->last()->medical_certificate_expiry_date->format('d/m/Y') }}
                                </b>
                            @endif
                        @endif
                    </div>

                    <div class="col-4">
                        <label class="form-label fs-6 d-block m-0">Status</label>
                        @if ($medical_certificates->last())
                            @if ($medical_certificates->last()->medical_certificate_expiry_date >= new DateTime('today'))
                                <span class="pill success-pill">
                                    <i class="fa-solid fa-circle me-1"></i> Attivo
                                </span>
                            @else
                                <span class="pill danger-pill">
                                    <i class="fa-solid fa-circle me-1"></i> Scaduto
                                </span>
                            @endif
                        @endif
                    </div>
                </div>

                {{-- status abbonamento --}}
                @php
                    $lastSub = $subscriptions->last();
                @endphp
                <div class="row mb-4">
                    <div class="col-12 mb-2 fw-bold pb-2 border-bottom sector-title">
                        <i class="fa-regular fa-id-card"></i> decorrenza Abbonamento
                    </div>

                    @if (isset($lastSub))
                        <div class="col-3 mb-2">
                            <label class="form-label fs-6 d-block m-0">Tipo di abbonamento</label>
                            @if ($lastSub->type === 'monthly')
                                <b>
                                    Mensile
                                </b>
                            @elseif ($lastSub->type === 'quarterly')
                                <b>
                                    4 mesi
                                </b>
                            @else
                                <b>
                                    Annuale
                                </b>
                            @endif
                        </div>

                        <div class="col-3">
                            <label class="form-label fs-6 d-block m-0">
                                Data inizio ultimo abbonamento
                            </label>
                            <b>
                                {{ $lastSub->start_date->format('d-m-Y') }}
                            </b>
                        </div>

                        <div class="col-3">
                            <label class="form-label fs-6 d-block m-0">
                                Data scadenza ultimo abbonamento
                            </label>
                            <b>
                                {{ $lastSub->base_end_date->format('d-m-Y') }}
                            </b>
                        </div>

                        <div class="col-3">
                            <label class="form-label fs-6 d-block m-0">
                                Status
                            </label>
                            @if ($lastSub->currentStatus() === 'active')
                                <span class="pill success-pill">
                                    <i class="fa-solid fa-circle me-1"></i> Attivo
                                </span>
                            @elseif ($lastSub->currentStatus() === 'suspended')
                                <span class="pill warning-pill">
                                    <i class="fa-solid fa-circle me-1"></i> Sospeso
                                </span>
                            @elseif ($lastSub->currentStatus() === 'pending')
                                <span class="pill info-pill">
                                    <i class="fa-solid fa-circle me-1"></i> In attesa
                                </span>
                            @else
                                <span class="pill danger-pill">
                                    <i class="fa-solid fa-circle me-1"></i> Scaduto
                                </span>
                            @endif
                        </div>
                    @else
                        <div class="col-12 mb-3">
                            <b>
                                Nessuna iscrizione
                            </b>
                        </div>
                    @endif
                </div>

                {{-- notes --}}
                <div class="row mb-3 pb-4 border-bottom">
                    <div class="col-12 mb-2 fw-bold pb-2 border-bottom sector-title">
                        <i class="fa-regular fa-note-sticky"></i> Note
                    </div>

                    <div class="col-12 mb-2">
                        <b>
                            @if ($client->notes)
                                {{ $client->notes }}
                            @else
                                Nessuna nota da segnalare
                            @endif
                        </b>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-2 d-flex align-items-center">
                        <a href="{{ route('index-client') }}" class="back-link">
                            <i class="fa-solid fa-arrow-left"></i> Torna indietro
                        </a>
                    </div>

                    <div class="col-10 d-flex align-items-center justify-content-end gap-3">
                        <a href="{{ route('edit-client', $client) }}" class="btn warning-button">
                            <i class="fa-solid fa-square-pen me-1"></i> Aggiorna dati
                        </a>
                        <a href="{{ route('show-subscriptions', $client) }}" class="btn see-client-info">
                            <i class="fa-solid fa-square-pen me-1"></i> Gestisci abbonamento
                        </a>
                        <a href="{{ route('show-medical-cert', $client) }}" class="btn info-button">
                            <i class="fa-solid fa-square-pen me-1"></i> Gestisci C.M.
                        </a>
                        <button type="button" class="btn danger-button" data-bs-toggle="modal"
                            data-bs-target="#exampleModal-{{ $client->id }}">
                            <i class="fa-solid fa-user-xmark me-1"></i> Elimina cliente
                        </button>

                        {{-- delete client modal --}}
                        <div class="modal fade" id="exampleModal-{{ $client->id }}" tabindex="-1"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0">
                                    <div class="modal-header text-white" style="background-color: var(--color-danger)">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Elimina cliente</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Sei sicuro di voler eliminare questo cliente? L'azione non può essere annullata

                                        <form action="{{ route('destroy-client', $client) }}" method="POST">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn danger-button mt-3 w-100">Elimina</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
