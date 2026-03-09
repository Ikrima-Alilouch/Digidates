<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DigiDate – Jouw startpagina</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 bg-gradient-to-br from-rose-900 via-slate-950 to-slate-900 text-slate-100">
    @include('layouts.navbar')

    <main class="mx-auto max-w-5xl px-4 py-8 space-y-8">
        <section>
            <h1 class="text-2xl font-semibold text-rose-100">
                Welkom terug,
                {{ auth()->user()->first_name ?? auth()->user()->name ?? auth()->user()->email }}
            </h1>
            <p class="mt-2 text-sm text-slate-300">
                Dit is jouw DigiDate startpagina. Vanaf hier ga je naar je matches, likes en gesprekken.
            </p>
        </section>

        <section class="grid gap-4 md:grid-cols-3 text-sm">
            <div class="rounded-2xl border border-slate-800/80 bg-slate-900/70 p-4">
                <h2 class="mb-1 text-sm font-semibold text-rose-100">Je profiel</h2>
                <p class="text-xs text-slate-300">
                    Werk je gegevens bij zodat anderen je beter leren kennen.
                </p>
                <p class="mt-3 text-[11px] text-slate-400">
                    Provincie: {{ auth()->user()->country ?? 'n.v.t.' }}<br>
                    Stad: {{ auth()->user()->location ?? 'n.v.t.' }}<br>
                    Geslacht: {{ auth()->user()->gender ?? 'n.v.t.' }}
                </p>
            </div>

            <div class="rounded-2xl border border-slate-800/80 bg-slate-900/70 p-4">
                <h2 class="mb-1 text-sm font-semibold text-rose-100">Interesses & tags</h2>
                @php $tags = auth()->user()->tags ?? collect(); @endphp
                @if($tags->isEmpty())
                    <p class="text-xs text-slate-300">
                        Je hebt nog geen interesses geselecteerd. Voeg later tags toe om betere matches te krijgen.
                    </p>
                @else
                    <p class="mb-2 text-xs text-slate-300">Dit zijn je huidige interesses:</p>
                    <div class="flex flex-wrap gap-1.5 text-[11px]">
                        @foreach($tags as $tag)
                            <span class="rounded-full bg-rose-500/20 px-3 py-1 text-rose-100 border border-rose-400/40">
                                {{ $tag->name }}
                            </span>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="rounded-2xl border border-slate-800/80 bg-slate-900/70 p-4">
                <h2 class="mb-1 text-sm font-semibold text-rose-100">Matches & berichten</h2>
                <p class="text-xs text-slate-300">
                    Hier komen straks je matches en actieve gesprekken te staan.
                </p>
                <p class="mt-3 text-[11px] text-slate-400">
                    Voor nu kun je rustig je profiel invullen en je interesses kiezen.
                </p>
            </div>
        </section>
    </main>
</body>
</html>