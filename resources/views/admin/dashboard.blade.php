<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DigiDate – Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 bg-gradient-to-br from-slate-900 via-slate-950 to-slate-900 text-slate-100">
    @include('layouts.navbar')

    <main class="mx-auto max-w-5xl px-4 py-8 space-y-4">
        <h1 class="text-2xl font-semibold text-rose-100">
            Admin dashboard
        </h1>
        <p class="text-sm text-slate-300">
            Hier kun je later gebruikers beheren, geblokkeerde accounts bekijken en rapportages zien.
        </p>

        <div class="mt-4 rounded-2xl border border-slate-800/80 bg-slate-900/70 p-4">
            <h2 class="text-sm font-semibold text-slate-200 mb-2">Snelle info</h2>
            <p class="text-xs text-slate-400">
                Ingelogd als: {{ auth()->user()->email }} (rol: {{ auth()->user()->role }})
            </p>
        </div>
    </main>
</body>
</html>

