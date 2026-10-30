@extends('layouts/trainer-default')

@section('title', '- Programmi - Settimane - Allenamenti')

@section('content')
    <div class="d-flex gap-2 p-5 pb-4 justify-content-between align-items-end">
        <div class="d-flex gap-2">
            <i class="fa-solid fa-list-ol fs-1 me-2" id="ana-icon"></i>
            <div class="d-flex flex-column">
                <h2 class="mb-1 dash-title fw-bold">Allenamenti</h2>
                <p class="m-0 dash-text">Settimana di allenamento {{ $client->first_name }} {{ $client->last_name }}</p>
            </div>
        </div>

        <form action="{{ route('store-session', $week) }}" method="POST">
            @csrf

            <button class="btn success-button">
                <i class="fa-solid fa-plus"></i> Nuovo allenamento
            </button>
        </form>
    </div>

    <div class="px-5 pb-5">
        <ul class="list-group d-flex flex-column gap-3 mb-3">
            @forelse ($sessions as $session)
                <li
                    class="list-group-item d-flex align-items-center justify-content-between rounded border-0 shadow-sm p-3">
                    <div class="heading">
                        <div class="heading-icon">
                            <i class="fa-solid fa-dumbbell"></i>
                        </div>

                        @if (count($session->exercises) > 1)
                            <p class="m-0">
                                <b>Allenamento {{ $loop->iteration }}</b>
                                <br>
                                {{ count($session->exercises) }} esercizi registrati
                            </p>
                        @elseif (count($session->exercises) === 1)
                            <p class="m-0">
                                <b>Allenamento {{ $loop->iteration }}</b>
                                <br>
                                {{ count($session->exercises) }} esercizio registrato
                            </p>
                        @else
                            <p class="m-0">
                                <b>Allenamento {{ $loop->iteration }}</b>
                                <br>
                                <b>
                                    Nessun esercizio registrato
                                </b>
                            </p>
                        @endif
                    </div>

                    <div class="program-actions">
                        <a href="{{ route('exercises', $session) }}" class="btn info-button me-2">
                            <i class="fa-solid fa-arrow-up" style="rotate: 45deg"></i> Apri allenamento
                        </a>

                        <button type="button" class="btn danger-button" data-bs-toggle="modal"
                            data-bs-target="#exampleModal-{{ $session->id }}">
                            <i class="fa-solid fa-xmark"></i> Elimina allenamento
                        </button>

                        {{-- delete client modal --}}
                        <div class="modal fade" id="exampleModal-{{ $session->id }}" tabindex="-1"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header text-white" style="background-color: var(--color-danger)">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Elimina allenamento</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Sei sicuro di voler eliminare questo allenamento? L'azione non può essere annullata
                                        <form action="{{ route('destroy-session', compact('week', 'session')) }}"
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
                </li>
            @empty
                <b>
                    Nessun allenamento in scheda
                </b>
            @endforelse
        </ul>

        <div class="pt-3">
            <a href="{{ route('weeks', $program[0]->id) }}" class="back-link">
                <i class="fa-solid fa-arrow-left"></i> Torna indietro
            </a>
        </div>
    </div>
@endsection
