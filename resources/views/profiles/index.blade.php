<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DigiDate – Ontdek Profielen</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 bg-gradient-to-br from-rose-900 via-slate-950 to-slate-900 text-slate-100">
    @include('layouts.navbar')

    <main class="mx-auto max-w-6xl px-4 py-8 space-y-8">
        <section class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-semibold text-rose-100">Ontdek Profielen</h1>
                <p class="mt-2 text-sm text-slate-300">
                    Vind matches op basis van gedeelde interesses. Profielen met de meeste overlap staan bovenaan!
                </p>
            </div>
            <a href="{{ route('profiles.edit') }}" class="inline-flex items-center gap-2 rounded-xl bg-slate-800 border border-slate-700 px-4 py-2 text-sm font-medium text-slate-200 shadow-sm hover:bg-slate-700 hover:text-white transition">
                Bewerk Jouw Profiel
            </a>
        </section>

        <section class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @forelse ($users as $user)
                @include('components.profile-card', ['user' => $user])
            @empty
                <div class="col-span-full rounded-2xl border border-slate-800/80 bg-slate-900/70 p-8 text-center">
                    <p class="text-slate-300">Geen profielen gevonden om weer te geven.</p>
                </div>
            @endforelse
        </section>
    </main>
</body>
</html>
