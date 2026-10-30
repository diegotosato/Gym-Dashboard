@extends('layouts/trainer-default')

@section('title', '- Programmi - Settimane')

@section('content')
    <div class="d-flex gap-2 p-5 pb-4 justify-content-between align-items-end">
        <div class="d-flex gap-2">
            <i class="fa-solid fa-list-ol fs-1 me-2" id="ana-icon"></i>
            <div class="d-flex flex-column">
                <h2 class="mb-1 dash-title fw-bold">Settimane di lavoro</h2>
                <p class="m-0 dash-text">Programma {{ $client[0]->first_name }} {{ $client[0]->last_name }}</p>
            </div>
        </div>

        <form action="{{ route('store-week', $program) }}" method="POST">
            @csrf

            <button class="btn success-button">
                <i class="fa-solid fa-plus"></i> Nuova settimana
            </button>
        </form>
    </div>

    <div class="px-5 pb-5">
        <ul class="list-group d-flex flex-column gap-3 mb-3">
            @forelse ($weeks as $week)
                <li
                    class="list-group-item d-flex align-items-center justify-content-between rounded border-0 shadow-sm p-3">
                    <div class="heading">
                        <div class="heading-icon">
                            <i class="fa-solid fa-calendar-week"></i>
                        </div>

                        @if (count($week->sessions) > 1)
                            <p class="m-0">
                                <b>Settimana {{ $loop->iteration }}</b>
                                <br>
                                {{ count($week->sessions) }} allenamenti registrati
                            </p>
                        @elseif (count($week->sessions) === 1)
                            <p class="m-0">
                                <b>Settimana {{ $loop->iteration }}</b>
                                <br>
                                {{ count($week->sessions) }} allenamento registrato
                            </p>
                        @else
                            <p class="m-0">
                                <b>Settimana {{ $loop->iteration }}</b>
                                <br>
                                <b>
                                    Nessun allenamento registrato
                                </b>
                            </p>
                        @endif
                    </div>

                    <div class="program-actions">
                        <a href="{{ route('sessions', $week) }}" class="btn info-button me-2">
                            <i class="fa-solid fa-arrow-up" style="rotate: 45deg"></i> Apri settimana
                        </a>

                        <button type="button" class="btn danger-button" data-bs-toggle="modal"
                            data-bs-target="#exampleModal-{{ $week->id }}">
                            <i class="fa-solid fa-xmark"></i> Elimina settimana
                        </button>

                        {{-- delete client modal --}}
                        <div class="modal fade" id="exampleModal-{{ $week->id }}" tabindex="-1"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header text-white" style="background-color: var(--color-danger)">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Elimina settimana</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Sei sicuro di voler eliminare questa settimana? L'azione non può essere annullata
                                        <form action="{{ route('destroy-week', compact('program', 'week')) }}"
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
                    Nessuna settimana di lavoro presente
                </b>
            @endforelse
        </ul>

        <div class="pt-3">
            <a href="{{ route('programs', $client[0]) }}" class="back-link">
                <i class="fa-solid fa-arrow-left"></i> Torna indietro
            </a>
        </div>
    </div>
@endsection
