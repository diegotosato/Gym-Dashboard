<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>GymManager - Login</title>

    {{-- icona --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon_white.ico') }}">

    {{-- bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    {{-- icone fontawesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    {{-- import css e js with Vite --}}
    @vite(['resources/css/app.css', 'resources/css/login.css', 'resources/css/header/header.css'])
</head>

<body class="d-flex align-items-center justify-content-center"
    style="background-color: var(--color-primary); width: 100%; height: 100vh">

    @if (session('success'))
        <div class="fixed-top d-flex justify-content-center mt-5">
            <div class="alert session-success" role="alert" style="width: 450px">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="fixed-top d-flex justify-content-center mt-5">
            <div class="alert session-error" role="alert" style="width: 450px">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        </div>
    @endif


    <div class="login">
        <div class="d-flex align-items-center justify-content-center gap-2 mb-4 text-white text-decoration-none">
            <div id="logo_palestra">
                <img src="{{ asset('logo_white.png') }}" alt="logo_palestra" class="w-100 d-block">
            </div>
            <div class="logo-texts">
                <h5 class="m-0">GYMManager</h5>
                <small class="m-0">La tua palestra, sempre con te</small>
            </div>
        </div>

        <div class="login-module py-4 px-5">
            <h4 class="module-title m-0 text-center fw-bold">
                Bentornato
            </h4>
            <p class="module-subtitle m-0 text-center mb-4">
                Accedi al tuo pannello di gestione
            </p>

            <form action="{{ route('login') }}" method="POST" id="login-form">
                @csrf

                <div class="login-module-div mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control ps-5" id="email" name="email"
                        class="py-1 ps-5 pe-3 searchbar" placeholder="name@example.com" required>
                    <i class="fa-solid fa-envelope"></i>
                </div>

                <div class="login-module-div mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control ps-5" id="password" name="password"
                        class="py-1 ps-5 pe-3 searchbar" placeholder="••••••••" required>
                    <i class="fa-solid fa-lock"></i>
                </div>

                <div class="mb-3 pt-3 d-flex flex-column">
                    <button class="btn text-white success-button mb-2" type="submit">
                        <i class="fa-solid fa-right-to-bracket"></i> Accedi
                    </button>

                    <a href="/add-new-user" class="back-link text-center">
                        Registra nuovo utente
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- script bootstrap --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>
</body>

</html>
