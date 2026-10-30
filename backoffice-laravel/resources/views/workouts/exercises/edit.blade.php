@extends('layouts/trainer-default')

@section('title', '- Programmi - Settimane - Allenamenti - Esercizi - Modifica Esercizio')

@section('content')
    <div class="d-flex gap-2 p-5 pb-4 justify-content-between align-items-end">
        <div class="d-flex gap-2">
            <i class="fa-solid fa-list-ol fs-1 me-2" id="ana-icon"></i>
            <div class="d-flex flex-column">
                <h2 class="mb-1 dash-title fw-bold">Modifica esercizio</h2>
                <p class="m-0 dash-text">Modifica esercizio {{ $client->first_name }} {{ $client->last_name }}</p>
            </div>
        </div>

        <a href="{{ route('exercises', $session) }}" class="btn px-3 back-button">
            <i class="fa-solid fa-arrow-left"></i> Torna agli esercizi
        </a>
    </div>

    <div class="px-5 pb-5">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body px-5 py-4">
                <form action="{{ route('update-exercise', compact('session', 'exercise')) }}" id="edit-client-form"
                    method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row mb-3 pb-5 border-bottom">
                        <div class="col-12 mb-2 fw-bold pb-2 border-bottom sector-title">
                            <i class="fa-solid fa-square-pen me-2"></i> Modifica esercizio
                        </div>

                        {{-- esercizio --}}
                        <div class="col-9 mb-2">
                            <label for="exercise_name" class="form-label fs-6">Esercizio</label>
                            <input type="text" class="form-control" id="exercise_name" name="exercise_name"
                                value="{{ old('exercise_name', $exercise->exercise_name) }}" required>
                        </div>

                        {{-- serie --}}
                        <div class="col-1">
                            <label for="sets" class="form-label fs-6">Serie</label>
                            <input type="number" name="sets" id="sets" class="form-control"
                                value="{{ old('sets', $exercise->sets) }}">
                        </div>

                        {{-- ripetiziono --}}
                        <div class="col-1">
                            <label for="reps" class="form-label fs-6">Ripetizioni</label>
                            <input type="number" name="reps" id="reps" class="form-control"
                                value="{{ old('reps', $exercise->reps) }}">
                        </div>

                        {{-- peso --}}
                        <div class="col-1">
                            <label for="weight" class="form-label fs-6">Peso (kg)</label>
                            <input type="number" name="weight" id="weight" class="form-control"
                                value="{{ old('weight', $exercise->weight) }}">
                        </div>

                        {{-- notes --}}
                        <div class="col-12">
                            <label for="notes" class="form-label fs-6">Notes</label>
                            <textarea class="form-control" name="notes" id="notes" rows="2" style="resize: none">{{ old('notes', $exercise->notes) }}</textarea>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-6 d-flex align-items-center">
                            <a href="{{ route('exercises', $session) }}" class="back-link">
                                <i class="fa-solid fa-arrow-left"></i> Torna indietro
                            </a>
                        </div>

                        <div class="col-6 text-end">
                            <button type="submit" class="btn success-button">
                                <i class="fa-solid fa-circle-arrow-down"></i> Salva modifiche esercizio
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
