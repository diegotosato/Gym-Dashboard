<aside class="p-3 text-white">
    <div class="d-flex flex-column justify-content-start align-items-start">
        <a href="/dashboard" class="d-flex align-items-center gap-2 my-1 text-white text-decoration-none">
            <div id="logo_palestra">
                <img src="{{ asset('logo_white.png') }}" alt="logo_palestra" class="w-100 d-block">
            </div>
            <div class="logo-texts">
                <h5 class="m-0">GYMManager</h5>
                <small class="m-0">La tua palestra, sempre con te</small>
            </div>
        </a>

        <nav class="navbar w-100 my-nav">
            <ul class="navbar-nav w-100 d-flex flex-column gap-2">
                <li class="nav-item">
                    <a href="{{ route('trainer-dash') }}"
                        class="nav-link text-white px-3 {{ str_contains(request()->url(), 'trainer-dash') ? 'is_selected' : '' }}">
                        <i class="fa-solid fa-user-group me-4"></i> Clienti
                    </a>
                </li>
            </ul>
        </nav>
    </div>


    @if (session('success'))
        <div class="session-box">
            <div class="alert session-success" role="alert">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="session-box">
            <div class="alert session-error" role="alert">
                @foreach ($errors->all() as $error)
                    <i class="fa-solid fa-circle-exclamation"></i> {{ $error }}
                @endforeach
            </div>
        </div>
    @endif

    <div class="gym-build d-flex flex-column justify-content-end p-3 gap-2">
        <div id="logo_palestra">
            <img src="{{ asset('logo_white.png') }}" alt="logo_palestra" class="w-100 d-block mb-2">
        </div>
        <p class="m-0">
            Costruiamo insieme il tuo benessere
        </p>
        <small>
            Più forza. Più salute. Ogni giorno
        </small>
    </div>
</aside>

@if (auth()->user())
    <header
        class="py-3 pe-3 ps-5 d-flex align-items-center {{ str_contains(request()->url(), 'trainer-dash') ? 'justify-content-between' : 'justify-content-end' }} shadow-sm"
        style="border-bottom: 2px solid var(--color-trainer);">

        @if (str_contains(request()->url(), 'trainer-dash'))
            <form action="{{ route('trainer-dash') }}" method="GET" class="d-flex align-items-start gap-3"
                id="searchbar-form">
                <div class="searchbar-div">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="search" id="search" class="py-1 ps-5 pe-3 searchbar"
                        placeholder="Cerca cliente..." value="{{ request('search') }}">
                </div>
                <button type="submit" class="btn px-3">
                    <i class="fa-solid fa-magnifying-glass"></i> Search
                </button>
                <a href="{{ route('trainer-dash') }}" class="btn px-3">
                    <i class="fa-solid fa-arrow-rotate-left"></i> Reset
                </a>
            </form>
        @endif

        <div class="manage-user d-flex align-items-center gap-3 dropdown">

            <div class="user-avatar text-white fs-4" style="background-color: var(--color-trainer);">
                <i class="fa-solid fa-user"></i>
            </div>

            <div class="user-infos d-flex flex-column" style="color: var(--color-trainer);">
                <b class="d-block">Bentornato, {{ auth()->user()->name }}</b>
                <small>{{ ucfirst(auth()->user()->role->role) }}</small>
            </div>

            <div class="dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false"
                style="cursor:pointer; color: var(--color-trainer);">
            </div>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="dropdown-item text-danger">
                            Log Out
                        </button>
                    </form>
                </li>
            </ul>

        </div>
    </header>
@endif
