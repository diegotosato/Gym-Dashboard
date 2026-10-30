@extends('layouts/trainer-default')

@section('title', ' - Trainer')

@section('content')
    <div class="d-flex gap-2 p-5 justify-content-between align-items-end">
        <div class="d-flex gap-2">
            <i class="fa-solid fa-table-list fs-1 me-2" id="ana-icon"></i>
            <div class="d-flex flex-column">
                <h2 class="mb-1 dash-title fw-bold">Lista clienti</h2>
                <p class="m-0 dash-text">Elenco di tutti i clienti della palestra</p>
            </div>
        </div>
    </div>

    <div class="d-flex align-items-center justify-content-between px-5">
        <table class="align-middle" id="recep-table">
            <thead class="text-white">
                <tr class="text-uppercase">
                    <th scope="col">Nome e cognome</th>
                    <th scope="col" style="width: 220px;">Azioni</th>
                </tr>
            </thead>
            <tbody class="table-group-divider">
                @foreach ($clients as $client)
                    <tr>
                        <td>{{ $client->first_name }} {{ $client->last_name }}</td>
                        <td style="white-space: nowrap;">
                            <a href="{{ route('programs', $client) }}" class="btn btn-sm info-button">
                                <i class="fa-solid fa-dumbbell"></i>
                                Programmi
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-3 d-flex justify-content-center">
        {{ $clients->links('pagination::bootstrap-5') }}
    </div>
@endsection
