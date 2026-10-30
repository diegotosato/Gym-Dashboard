<!doctype html>
<html lang="it">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Le tue credenziali</title>
</head>

<body style="margin: 0; padding: 24px; background-color: #f4f6f8; color: #20242a; font-family: Arial, sans-serif;">
    <main
        style="max-width: 560px; margin: 0 auto; padding: 32px; background-color: #eef0fb; border-radius: 10px; box-shadow: 0 0 5px #a7a7a7;">
        <h1 style="margin: 0 0 10px; font-size: 24px; color: #4f46e5">Benvenuto, {{ $client->first_name }}
            {{ $client->last_name }}</h1>
        <p style="margin: 0 0 20px; line-height: 1.5; color: #172443">
            Il tuo account è stato creato. Usa queste credenziali per accedere:
        </p>

        <div style="background-color: #4f46e5; padding: 24px; border-radius: 10px; box-shadow: 0 0 5px #a7a7a7;">
            <p style="color: #eef0fb">
                Nome utente: <b style="color:white;">{{ $data_view['username'] }}</b>
            </p>
            <p style="color: #eef0fb">
                Password: <b style="color:white;">{{ $data_view['password'] }}</b>
            </p>
        </div>

        <p style="margin: 24px 0 0; line-height: 1.5; color: #4f46e5">
            <b>
                IMPORTANTE: Conserva questa email in un luogo sicuro.
            </b>
        </p>
    </main>
</body>

</html>
