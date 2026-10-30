@extends('layouts/default')

@section('title', '- ' . $client->first_name . ' ' . $client->last_name . ' - Certificati medici')

@section('content')
    <div class="d-flex gap-2 p-5 pb-4 justify-content-between align-items-end">
        <div class="d-flex gap-2">
            <i class="fa-solid fa-square-pen fs-1 me-2" id="ana-icon"></i>
            <div class="d-flex flex-column">
                <h2 class="mb-1 dash-title fw-bold">{{ $client->first_name }} {{ $client->last_name }}</h2>
                <p class="m-0 dash-text">Gestisci certificati medici</p>
            </div>
        </div>

        <a href="{{ route('show-client', $client) }}" class="btn px-3 back-button">
            <i class="fa-solid fa-arrow-left"></i> Torna alle info cliente
        </a>
    </div>

    <div class="px-5 pb-5">
        <div class="card border-0 shadow-sm">
            <div class="card-body px-5 py-4">
                <div class="row mb-1">
                    <div class="col-12 mb-2 fw-bold pb-2 border-bottom sector-title">
                        <i class="fa-solid fa-clock"></i> storico consegne
                    </div>
                </div>

                {{-- storico rinnovi --}}
                @if (count($meds) > 0)
                    <table class="align-middle mb-5" id="recep-table">
                        <thead class="text-white">
                            <tr class="text-uppercase">
                                <th scope="col"></th>
                                <th scope="col">Data emissione</th>
                                <th scope="col">Data fine validità</th>
                                <th scope="col">Status</th>
                                <th scope="col">Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($meds as $medical_certificate)
                                <tr>
                                    <td scope="row">
                                        <b>
                                            {{ $loop->iteration }}
                                        </b>
                                    </td>
                                    <td>{{ $medical_certificate->medical_certificate_start_date->format('d-m-Y') }}</td>
                                    <td>{{ $medical_certificate->medical_certificate_expiry_date->format('d-m-Y') }}</td>
                                    <td>
                                        @if ($medical_certificate->currentStatus() === 'active')
                                            <span class="pill success-pill">
                                                <i class="fa-solid fa-circle me-1"></i> Valido
                                            </span>
                                        @else
                                            <span class="pill danger-pill">
                                                <i class="fa-solid fa-circle me-1"></i> Scaduto
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('edit-medical-cert', $medical_certificate) }}"
                                            class="btn btn-sm info-button">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm danger-button" data-bs-toggle="modal"
                                            data-bs-target="#deleteSubModal-{{ $medical_certificate->id }}">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>

                                        {{-- delete subscription modal --}}
                                        <div class="modal fade" id="deleteSubModal-{{ $medical_certificate->id }}"
                                            tabindex="-1"
                                            aria-labelledby="deleteSubModalLabel-{{ $medical_certificate->id }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content border-0">
                                                    <div class="modal-header text-white"
                                                        style="background-color: var(--color-danger)">
                                                        <h1 class="modal-title fs-5"
                                                            id="deleteSubModalLabel-{{ $medical_certificate->id }}">
                                                            Elimina certificato medico</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Sei sicuro di voler eliminare definitivamente questa consegna del
                                                        certificato medico? L'azione non può essere annullata
                                                        <form
                                                            action="{{ route('destroy-medical-cert', compact('medical_certificate')) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')

                                                            <button type="submit"
                                                                class="btn btn-danger mt-3">Elimina</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="col-12 mb-5">
                        <b>
                            Nessuna consegna
                        </b>
                    </div>
                @endif

                {{-- nuova consegna --}}
                <div class="row mb-3">
                    <div class="col-12 mb-2 fw-bold pb-2 border-bottom sector-title">
                        <i class="fa-solid fa-square-plus"></i> Nuova consegna
                    </div>

                    <form action="{{ route('store-medical-cert', $client) }}" method="POST" id="edit-client-form">
                        @csrf

                        <div class="row">
                            <div class="col-5">
                                <label for="medical_certificate_start_date" class="form-label">
                                    Data nuovo rilascio del C.M.
                                </label>
                                <input type="date" class="form-control" id="medical_certificate_start_date"
                                    name="medical_certificate_start_date" required>
                            </div>


                            <div class="col-5">
                                <label for="medical_certificate_expiry_date" class="form-label">
                                    Data nuova scadenza del C.M.
                                </label>
                                <input type="date" class="form-control" id="medical_certificate_expiry_date"
                                    name="medical_certificate_expiry_date" required>
                            </div>

                            <div class="col-2 d-flex align-items-end">
                                <button type="submit" class="btn success-button">
                                    <i class="fa-solid fa-check"></i> Salva consegna
                                </button>
                            </div>
                        </div>

                        <div class="row mt-4 mb-3 pb-4 border-bottom"></div>

                        <div class="row mb-4">
                            <div class="col-6 d-flex align-items-center">
                                <a href="{{ route('show-client', $client) }}" class="back-link">
                                    <i class="fa-solid fa-arrow-left"></i> Torna indietro
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
