<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DigiDate – Nieuw wachtwoord</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 bg-gradient-to-br from-rose-900 via-slate-950 to-slate-900 text-slate-100">
    @include('layouts.navbar')
    <div class="w-full max-w-md mx-auto mt-10 px-4">
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-semibold tracking-tight text-rose-100">
                Nieuw wachtwoord instellen
            </h1>
            <p class="mt-2 text-sm text-slate-300">
                Kies een sterk wachtwoord van minimaal 8 tekens.
            </p>
        </div>

        @if ($errors->any())
            <div class="mb-4 rounded-xl bg-rose-500/10 border border-rose-500/60 px-4 py-3 text-sm text-rose-100">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form
            method="POST"
            action="{{ route('password.update', ['token' => $token]) }}"
            class="rounded-2xl bg-slate-900/70 border border-slate-800/80 shadow-2xl shadow-black/40 backdrop-blur"
        >
            @csrf
            <div class="px-6 pt-6 pb-4 space-y-4">
                <div class="space-y-1.5">
                    <label for="password" class="block text-sm font-medium text-slate-200">
                        Nieuw wachtwoord
                    </label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        required
                        class="block w-full rounded-xl border border-slate-700/80 bg-slate-900/70 px-3 py-2.5 text-sm text-slate-100 placeholder:text-slate-500 shadow-inner focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-rose-500/70 focus-visible:border-rose-400/60 transition"
                        placeholder="Minimaal 8 tekens"
                    >
                </div>

                <div class="space-y-1.5">
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-200">
                        Herhaal wachtwoord
                    </label>
                    <input
                        id="password_confirmation"
                        name="password_confirmation"
                        type="password"
                        required
                        class="block w-full rounded-xl border border-slate-700/80 bg-slate-900/70 px-3 py-2.5 text-sm text-slate-100 placeholder:text-slate-500 shadow-inner focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-rose-500/70 focus-visible:border-rose-400/60 transition"
                        placeholder="Nogmaals hetzelfde wachtwoord"
                    >
                </div>
            </div>

            <div class="px-6 pb-6 space-y-3">
                <button
                    type="submit"
                    class="inline-flex w-full items-center justify-center rounded-xl bg-rose-500 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-rose-900/50 hover:bg-rose-400 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-offset-slate-950 focus-visible:ring-rose-400 transition"
                >
                    Wachtwoord opslaan
                </button>

                <a
                    href="{{ route('login') }}"
                    class="inline-flex w-full items-center justify-center rounded-xl border border-slate-700/80 bg-slate-900/60 px-4 py-2.5 text-sm font-semibold text-slate-100 hover:bg-slate-800/80 transition"
                >
                    Terug naar inloggen
                </a>
            </div>
        </form>
    </div>
</body>
</html>

