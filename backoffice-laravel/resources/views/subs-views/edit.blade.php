@extends('layouts/default')

@section('title', '- ' . $client->first_name . ' ' . $client->last_name . ' - Abbonamenti')

@section('content')
    <div class="d-flex gap-2 p-5 pb-4 justify-content-between align-items-end">
        <div class="d-flex gap-2">
            <i class="fa-solid fa-square-pen fs-1 me-2" id="ana-icon"></i>
            <div class="d-flex flex-column">
                <h2 class="mb-1 dash-title fw-bold">{{ $client->first_name }} {{ $client->last_name }}</h2>
                <p class="m-0 dash-text">Gestisci abbonamento</p>
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
                        <i class="fa-solid fa-clock"></i> storico rinnovi
                    </div>
                </div>

                {{-- storico rinnovi --}}
                @if (count($subs) > 0)
                    <table class="align-middle mb-5" id="recep-table">
                        <thead class="text-white">
                            <tr class="text-uppercase">
                                <th scope="col"></th>
                                <th scope="col">Data inizio</th>
                                <th scope="col">Data fine</th>
                                <th scope="col">Tipologia</th>
                                <th scope="col">Status</th>
                                <th scope="col">Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($subs as $sub)
                                <tr>
                                    <td scope="row">
                                        @if ($loop->first)
                                            <b>
                                                Prima iscrizione
                                            </b>
                                        @else
                                            <b>
                                                {{ $loop->iteration }}° rinnovo
                                            </b>
                                        @endif
                                    </td>
                                    <td>{{ $sub->start_date->format('d-m-Y') }}</td>
                                    <td>{{ $sub->base_end_date->format('d-m-Y') }}</td>
                                    <td>
                                        @if ($sub->type === 'monthly')
                                            Mensile
                                        @elseif ($sub->type === 'quarterly')
                                            4 mesi
                                        @else
                                            Annuale
                                        @endif
                                    </td>
                                    @if ($sub->currentStatus() === 'active')
                                        <td>
                                            <span class="pill success-pill">
                                                <i class="fa-solid fa-circle me-1"></i> Attivo
                                            </span>
                                        </td>
                                    @elseif ($sub->currentStatus() === 'suspended')
                                        <td>
                                            <span class="pill warning-pill">
                                                <i class="fa-solid fa-circle me-1"></i> Sospeso
                                            </span>
                                        </td>
                                    @elseif ($sub->currentStatus() === 'pending')
                                        <td>
                                            <span class="pill info-pill">
                                                <i class="fa-solid fa-circle me-1"></i> In attesa
                                            </span>
                                        </td>
                                    @else
                                        <td>
                                            <span class="pill danger-pill">
                                                <i class="fa-solid fa-circle me-1"></i> Scaduto
                                            </span>
                                        </td>
                                    @endif
                                    <td>
                                        <a href="{{ route('edit-subscription', $sub) }}" class="btn btn-sm info-button">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm danger-button" data-bs-toggle="modal"
                                            data-bs-target="#deleteSubModal-{{ $sub->id }}">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>

                                        {{-- delete subscription modal --}}
                                        <div class="modal fade" id="deleteSubModal-{{ $sub->id }}" tabindex="-1"
                                            aria-labelledby="deleteSubModalLabel-{{ $sub->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content border-0">
                                                    <div class="modal-header text-white"
                                                        style="background-color: var(--color-danger)">
                                                        <h1 class="modal-title fs-5"
                                                            id="deleteSubModalLabel-{{ $sub->id }}">
                                                            Elimina rinnovo</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Sei sicuro di voler eliminare definitivamente questo rinnovo?
                                                        L'azione non può essere annullata
                                                        <form action="{{ route('destroy-subscription', $sub) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')

                                                            <button type="submit"
                                                                class="btn danger-button mt-3 w-100">Elimina</button>
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
                            Nessuna iscrizione
                        </b>
                    </div>
                @endif

                {{-- storico sospensioni --}}
                <div class="row mb-1">
                    <div class="col-12 mb-2 fw-bold pb-2 border-bottom sector-title">
                        <i class="fa-solid fa-clock"></i> storico sospensioni
                    </div>
                </div>

                @if (count($suspensions) > 0)
                    <table class="align-middle mb-5" id="recep-table">
                        <thead style="background-color: var(--color-warning)">
                            <tr class="text-uppercase">
                                <th scope="col">Data inizio</th>
                                <th scope="col">Data fine</th>
                                <th scope="col">Status</th>
                                <th scope="col">Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- @dd($suspensions[0][0]) --}}
                            @foreach ($suspensions as $sus)
                                <tr>
                                    <td scope="row">
                                        {{ $sus[0]->start_date->format('d-m-Y') }}
                                    </td>
                                    <td>
                                        @if ($sus[0]->end_date !== null)
                                            {{ $sus[0]->end_date->format('d-m-Y') }}
                                        @else
                                            Nessuna data di fine sospensione
                                        @endif
                                    </td>
                                    @if ($lastSub->currentStatus() === 'suspended')
                                        <td>
                                            <span class="pill success-pill">
                                                <i class="fa-solid fa-circle me-1"></i> Sospensione attiva
                                            </span>
                                        </td>
                                    @else
                                        <td>
                                            <span class="pill danger-pill">
                                                <i class="fa-solid fa-circle me-1"></i> Sospensione finita
                                            </span>
                                        </td>
                                    @endif
                                    <td>
                                        <button type="button" class="btn btn-sm danger-button" data-bs-toggle="modal"
                                            data-bs-target="#deleteSuspensionModal-{{ $sus[0]->id }}">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>

                                        {{-- delete suspension modal --}}
                                        <div class="modal fade" id="deleteSuspensionModal-{{ $sus[0]->id }}"
                                            tabindex="-1" aria-labelledby="deleteSuspensionModalLabel-{{ $sus[0]->id }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content border-0">
                                                    <div class="modal-header text-white"
                                                        style="background-color: var(--color-danger)">
                                                        <h1 class="modal-title fs-5"
                                                            id="deleteSuspensionModalLabel-{{ $sus[0]->id }}">
                                                            Elimina sospensione</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Sei sicuro di voler eliminare definitivamente questa sospensione?
                                                        L'azione non può essere annullata
                                                        <form action="{{ route('destroy-suspension', $sus[0]) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')

                                                            <button type="submit"
                                                                class="btn danger-button mt-3 w-100">Elimina</button>
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
                            Nessuna sospensione
                        </b>
                    </div>
                @endif


                {{-- nuovo rinnovo --}}
                <div class="row mb-5">
                    <div class="col-12 mb-2 fw-bold pb-2 border-bottom sector-title">
                        <i class="fa-solid fa-square-plus"></i> Nuovo rinnovo
                    </div>

                    <form action="{{ route('store-subscription', $client) }}" method="POST" id="edit-client-form">
                        @csrf

                        <div class="row">
                            <div class="col-5">
                                <label for="start_date" class="form-label">Data di inizio nuova decorrenza</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" required>
                            </div>

                            <div class="col-5">
                                <label for="sub_type" class="form-label d-block mb-3">Tipo di abbonamento</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="sub_type" id="monthly"
                                        value="monthly">
                                    <label class="form-check-label" for="monthly">Mensile</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="sub_type" id="quarterly"
                                        value="quarterly">
                                    <label class="form-check-label" for="quarterly">4 mesi</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="sub_type" id="annual"
                                        value="annual">
                                    <label class="form-check-label" for="annual">Annuale</label>
                                </div>
                            </div>

                            <div class="col-2 d-flex align-items-end">
                                <button type="submit" class="btn success-button">
                                    <i class="fa-solid fa-check"></i> Salva rinnovo
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- sospensione abbonamento --}}
                <div class="row mb-3">
                    <div class="col-12 mb-2 fw-bold pb-2 border-bottom sector-title">
                        <i class="fa-solid fa-circle-stop"></i> Sospendi abbonamento
                    </div>

                    <form action="{{ route('store-suspension', compact('lastSub', 'client')) }}" method="POST"
                        id="edit-client-form">
                        @csrf

                        <div class="row">
                            <div class="col-5">
                                <label for="start_date" class="form-label">
                                    Data di inizio sospensione
                                </label>
                                <input type="date" class="form-control" id="start_date" name="start_date" required>
                            </div>


                            <div class="col-5">
                                <label for="end_date" class="form-label">
                                    Data di fine sospensione
                                </label>
                                <input type="date" class="form-control" id="end_date" name="end_date" required>
                            </div>

                            <div class="col-2 d-flex align-items-end">
                                <button type="submit" class="btn success-button">
                                    <i class="fa-solid fa-check"></i> Salva sospensione
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="row mb-3 pb-4 border-bottom"></div>

                <div class="row mb-4">
                    <div class="col-6 d-flex align-items-center">
                        <a href="{{ route('show-client', $client) }}" class="back-link">
                            <i class="fa-solid fa-arrow-left"></i> Torna indietro
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- @dd($lastSub) --}}
@endsection
