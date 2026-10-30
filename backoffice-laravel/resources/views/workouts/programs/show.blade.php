@extends('layouts/trainer-default')

@section('title', '- ' . $client->first_name . ' ' . $client->last_name . ' - Programmi')

@section('content')
    <div class="d-flex gap-2 p-5 pb-4 justify-content-between align-items-end">
        <div class="d-flex gap-2">
            <i class="fa-solid fa-list-ol fs-1 me-2" id="ana-icon"></i>
            <div class="d-flex flex-column">
                <h2 class="mb-1 dash-title fw-bold">{{ $client->first_name }} {{ $client->last_name }}</h2>
                <p class="m-0 dash-text">Programmi cliente</p>
            </div>
        </div>

        <form action="{{ route('store-program', $client) }}" method="POST">
            @csrf

            <button class="btn success-button">
                <i class="fa-solid fa-plus"></i> Nuovo programma
            </button>
        </form>
    </div>

    <div class="px-5 pb-5">
        {{-- PROGRAMMI --}}
        @forelse ($programs as $program)
            <div class="accordion mb-3 shadow-sm" id="accordionProgram{{ $program->id }}">
                <div class="accordion-item border-0">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapse{{ $program->id }}" aria-expanded="false"
                            aria-controls="collapse{{ $program->id }}">
                            <i class="fa-solid fa-clipboard-list me-2"></i> Programma {{ $loop->iteration }}
                        </button>
                    </h2>

                    <div id="collapse{{ $program->id }}" class="accordion-collapse collapse workout-accordion border-0"
                        data-bs-parent="#accordionProgram{{ $program->id }}">
                        <div class="accordion-body pt-0">

                            {{-- AZIONI PROGRAMMA --}}
                            <div class="d-flex gap-3">
                                <a href="{{ route('weeks', $program) }}" class="btn info-button">
                                    <i class="fa-solid fa-up-right-and-down-left-from-center"></i> Apri programma
                                </a>

                                <button type="button" class="btn danger-button" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal-{{ $program->id }}">
                                    <i class="fa-solid fa-xmark"></i> Elimina programma
                                </button>

                                {{-- delete client modal --}}
                                <div class="modal fade" id="exampleModal-{{ $program->id }}" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0">
                                            <div class="modal-header text-white"
                                                style="background-color: var(--color-danger)">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Elimina
                                                    programma</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Sei sicuro di voler eliminare questo programma? L'azione non può essere
                                                annullata
                                                <form action="{{ route('destroy-program', compact('client', 'program')) }}"
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
                            </div>

                            {{-- SETTIMANE --}}
                            @foreach ($program->weeks as $week)
                                <div class="accordion mt-3" id="accordionWeek{{ $week->week_number }}">
                                    <div class="accordion-item border-0">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed fw-bold week-accordion-button"
                                                type="button" data-bs-toggle="collapse"
                                                data-bs-target="#collapseWeek{{ $week->week_number }}"
                                                aria-expanded="false" aria-controls="collapse{{ $week->week_number }}">
                                                Settimana {{ $loop->iteration }}
                                            </button>
                                        </h2>

                                        <div id="collapseWeek{{ $week->week_number }}"
                                            class="accordion-collapse collapse week-accordion border-0"
                                            data-bs-parent="#accordionWeek{{ $week->week_number }}">
                                            <div class="accordion-body week-accordion-body d-flex flex-column gap-3">

                                                {{-- ALLENAMENTI --}}
                                                @foreach ($week->sessions as $session)
                                                    <div class="accordion" id="accordionSession{{ $session->id }}">
                                                        <div class="accordion-item border-0">
                                                            <h2 class="accordion-header">
                                                                <button
                                                                    class="accordion-button collapsed fw-bold session-accordion-button"
                                                                    type="button" data-bs-toggle="collapse"
                                                                    data-bs-target="#collapseSession{{ $session->id }}"
                                                                    aria-expanded="false"
                                                                    aria-controls="collapse{{ $session->id }}">
                                                                    Allenamento {{ $loop->iteration }}
                                                                </button>
                                                            </h2>
                                                            <div id="collapseSession{{ $session->id }}"
                                                                class="accordion-collapse collapse"
                                                                data-bs-parent="#accordionSession{{ $session->id }}">
                                                                <div class="accordion-body pt-0">

                                                                    {{-- ESERCIZI --}}
                                                                    <ul class="list-group d-flex flex-column gap-2">
                                                                        @foreach ($session->exercises as $exercise)
                                                                            <li class="list-group-item border-0 rounded"
                                                                                style="background-color: var(--color-primary-sub);">
                                                                                <span class="fw-bold me-3"
                                                                                    style="color: var(--color-primary)">
                                                                                    {{ $exercise->exercise_name }}
                                                                                </span>
                                                                                Serie:
                                                                                <b>
                                                                                    {{ $exercise->sets }}
                                                                                </b>
                                                                                - Ripetizioni:
                                                                                <b>
                                                                                    {{ $exercise->reps }}
                                                                                </b>
                                                                                - Peso:
                                                                                <b>
                                                                                    @if ($exercise->weight)
                                                                                        {{ $exercise->weight }} kg
                                                                                    @else
                                                                                    @endif
                                                                                </b>
                                                                                @if ($exercise->notes)
                                                                                    - Note:
                                                                                    <b>
                                                                                        {{ $exercise->notes }}
                                                                                    </b>
                                                                                @else
                                                                                @endif
                                                                            </li>
                                                                        @endforeach
                                                                    </ul>
                                                                    {{-- FINE ESERCIZI --}}

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                                {{-- FINE ALLENAMENTI --}}

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            {{-- FINE SETTIMANE --}}

                        </div>
                    </div>
                </div>
            </div>
            {{-- FINE PROGRAMMI --}}
        @empty
            <b>
                Nessun programma presente
            </b>
        @endforelse


        <div class="pt-3">
            <a href="/trainer-dash" class="back-link">
                <i class="fa-solid fa-arrow-left"></i> Torna indietro
            </a>
        </div>
    </div>

    {{-- @dd($programs[0]->weeks) --}}

@endsection
