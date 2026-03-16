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
                Welkom,
                {{ auth()->user()->first_name}}
            </h1>
            <p class="mt-2 text-sm text-slate-300">
                Dit is jouw DigiDate startpagina. Vanaf hier ga je naar je matches, likes en gesprekken.
            </p>
        </section>
    </main>
</body>
</html>