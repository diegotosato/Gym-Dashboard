@extends('layouts/trainer-default')

@section('title', '- Programmi - Settimane - Allenamenti - Esercizi')

@section('content')
    <div class="d-flex gap-2 p-5 pb-4 justify-content-between align-items-end">
        <div class="d-flex gap-2">
            <i class="fa-solid fa-list-ol fs-1 me-2" id="ana-icon"></i>
            <div class="d-flex flex-column">
                <h2 class="mb-1 dash-title fw-bold">Esercizi</h2>
                <p class="m-0 dash-text">Allenamento {{ $client->first_name }} {{ $client->last_name }}</p>
            </div>
        </div>
    </div>

    <div class="px-5 pb-5">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body px-5 py-4">
                <form action="{{ route('store-exercise', $session) }}" id="edit-client-form" method="POST">
                    @csrf

                    <div class="row mb-4">
                        <div class="col-12 mb-2 fw-bold pb-2 border-bottom sector-title">
                            <i class="fa-solid fa-plus me-2"></i> Aggiungi esercizio
                        </div>

                        {{-- esercizio --}}
                        <div class="col-9 mb-2">
                            <label for="exercise_name" class="form-label fs-6">Esercizio</label>
                            <input type="text" class="form-control" id="exercise_name" name="exercise_name" required>
                        </div>

                        {{-- serie --}}
                        <div class="col-1">
                            <label for="sets" class="form-label fs-6">Serie</label>
                            <input type="number" name="sets" id="sets" class="form-control">
                        </div>

                        {{-- ripetiziono --}}
                        <div class="col-1">
                            <label for="reps" class="form-label fs-6">Ripetizioni</label>
                            <input type="number" name="reps" id="reps" class="form-control">
                        </div>

                        {{-- peso --}}
                        <div class="col-1">
                            <label for="weight" class="form-label fs-6">Peso (kg)</label>
                            <input type="number" name="weight" id="weight" class="form-control">
                        </div>

                        {{-- notes --}}
                        <div class="col-11">
                            <label for="notes" class="form-label fs-6">Notes</label>
                            <textarea class="form-control" name="notes" id="notes" rows="2" style="resize: none"></textarea>
                        </div>

                        <div class="col-1 d-flex align-items-end ">
                            <button type="submit" class="btn success-button" style="width: 100%;">
                                <i class="fa-solid fa-circle-arrow-down"></i> Salva
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- lista esercizi --}}
        <b class="mb-2 d-block">Lista esercizi</b>

        <ul class="list-group d-flex flex-column gap-3">
            @forelse ($exercises as $exercise)
                <li class="list-group-item rounded border-0 shadow-sm p-3">
                    <div class="row">
                        <div class="col d-flex align-items-center" style="color: var(--color-primary);">
                            <b>{{ $exercise->exercise_name }}</b>
                        </div>

                        <div class="col d-flex align-items-center">
                            Serie: <b class="ms-1 me-3">{{ $exercise->sets }}</b>
                            Ripetizioni: <b class="ms-1 me-3">{{ $exercise->reps }}</b>
                            Peso: <b class="ms-1 me-3">{{ $exercise->weight }} kg</b>
                        </div>

                        <div class="col d-flex align-items-center">
                            @if ($exercise->notes)
                                Note:<b class="ms-1">{{ $exercise->notes }}</b>
                            @else
                                Nessuna nota
                            @endif
                        </div>

                        <div class="col d-flex align-items-center justify-content-end gap-1">
                            <a href="{{ route('edit-exercise', compact('session', 'exercise')) }}"
                                class="btn info-button btn-sm">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <button type="button" class="btn danger-button btn-sm" data-bs-toggle="modal"
                                data-bs-target="#deleteSubModal-{{ $exercise->id }}">
                                <i class="fa-solid fa-trash"></i>
                            </button>

                            {{-- delete subscription modal --}}
                            <div class="modal fade" id="deleteSubModal-{{ $exercise->id }}" tabindex="-1"
                                aria-labelledby="deleteSubModalLabel-{{ $exercise->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header text-white" style="background-color: var(--color-danger)">
                                            <h1 class="modal-title fs-5" id="deleteSubModalLabel-{{ $exercise->id }}">
                                                Elimina esercizio
                                            </h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Sei sicuro di voler eliminare questo esercizio? L'azione non può essere
                                            annullata
                                            <form action="{{ route('destroy-exercise', compact('exercise', 'session')) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn danger-button w-100 mt-3">Elimina</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            @empty
                <b>
                    Nessun esercizio salvato
                </b>
            @endforelse
        </ul>

        <div class="pt-3">
            <a href="{{ route('sessions', $week[0]->id) }}" class="back-link">
                <i class="fa-solid fa-arrow-left"></i> Torna indietro
            </a>
        </div>
    </div>
@endsection
