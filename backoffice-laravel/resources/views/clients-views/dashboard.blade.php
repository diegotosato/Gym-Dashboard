@extends('layouts/default')

@section('title', '- Clienti')

@section('content')
    <div class="d-flex gap-2 p-5 justify-content-between align-items-end">
        <div class="d-flex gap-2">
            <i class="fa-solid fa-table-list fs-1 me-2" id="ana-icon"></i>
            <div class="d-flex flex-column">
                <h2 class="mb-1 dash-title fw-bold">Lista clienti</h2>
                <p class="m-0 dash-text">Elenco di tutti i clienti della palestra</p>
            </div>
        </div>

        <a href="/add-new-client" class="btn px-3 success-button">
            <i class="fa-solid fa-user-plus"></i> Registra nuovo cliente
        </a>
    </div>

    <div class="d-flex align-items-center justify-content-between px-5">
        <table class="align-middle" id="recep-table">
            <thead class="text-white">
                <tr class="text-uppercase">
                    <th scope="col">Nome e cognome</th>
                    <th scope="col">Email</th>
                    <th scope="col">Telefono</th>
                    <th scope="col">Validità C.M.</th>
                    <th scope="col">Stato Abb.</th>
                    <th scope="col">Azioni</th>
                </tr>
            </thead>
            <tbody>
                {{-- @dd($clients[0]) --}}
                @forelse ($clients as $client)
                    <tr>
                        <td scope="row">
                            <b>{{ $client->first_name }} {{ $client->last_name }}</b>
                        </td>
                        <td>
                            {{ $client->email }}
                        </td>
                        <td>
                            {{ $client->phone }}
                        </td>

                        @php $lastMed = $client->medical_certificates->last(); @endphp
                        @if ($lastMed && $lastMed->medical_certificate_expiry_date > new DateTime('today'))
                            <td>
                                <span class="pill success-pill">
                                    <i class="fa-solid fa-circle me-1"></i> Valido
                                </span>
                            </td>
                        @else
                            <td>
                                <span class="pill danger-pill">
                                    <i class="fa-solid fa-circle me-1"></i> Scaduto
                                </span>
                            </td>
                        @endif

                        @php $lastSub = $client->subscriptions->last(); @endphp
                        @if (!$lastSub)
                            <td></td>
                        @elseif ($lastSub->currentStatus() === 'active')
                            <td>
                                <span class="pill success-pill">
                                    <i class="fa-solid fa-circle me-1"></i> Attivo
                                </span>
                            </td>
                        @elseif ($lastSub->currentStatus() === 'suspended')
                            <td>
                                <span class="pill warning-pill">
                                    <i class="fa-solid fa-circle me-1"></i> Sospeso
                                </span>
                            </td>
                        @elseif ($lastSub->currentStatus() === 'pending')
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
                            <a href="{{ route('show-client', $client) }}" class="btn btn-sm see-client-info">
                                <i class="fa-solid fa-circle-info"></i> Vedi info
                            </a>
                        </td>
                    </tr>
                @empty
                @endforelse

            </tbody>
        </table>
    </div>

    <div class="mt-3 d-flex justify-content-center">
        {{ $clients->links('pagination::bootstrap-5') }}
    </div>
@endsection
