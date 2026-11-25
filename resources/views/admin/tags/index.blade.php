<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DigiDate – Tags Beheer</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 bg-gradient-to-br from-rose-900 via-slate-950 to-slate-900 text-slate-100">
    @include('layouts.navbar')

    <main class="mx-auto max-w-6xl px-4 py-8 space-y-8">
        <section class="flex flex-col flex-row items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-semibold text-rose-100">Tags Beheer</h1>
                <p class="mt-2 text-sm text-slate-300">
                    Voeg nieuwe interesses (tags) toe of verwijder ze.
                </p>
            </div>
        </section>

        @if(session('success'))
            <div class="rounded-xl bg-green-500/20 border border-green-500/50 p-4 text-green-200">
                {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="rounded-xl bg-red-500/20 border border-red-500/50 p-4 text-red-200">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <section class="col-span-1 rounded-2xl border border-slate-800/80 bg-slate-900/70 p-6 shadow-xl h-fit">
                <h2 class="text-xl font-medium text-rose-100 mb-4">Nieuwe Tag</h2>
                <form action="{{ route('admin.tags.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-300">Tag Naam</label>
                        <input type="text" name="name" id="name" required class="mt-1 block w-full rounded-md border-slate-700 bg-slate-800 text-white shadow-sm focus:border-rose-500 focus:ring-rose-500 sm:text-sm" placeholder="bijv. Lezen">
                    </div>
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-rose-600 hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 transition">
                        Toevoegen
                    </button>
                </form>
            </section>

            <section class="col-span-2 rounded-2xl border border-slate-800/80 bg-slate-900/70 p-6 shadow-xl">
                <h2 class="text-xl font-medium text-rose-100 mb-4">Bestaande Tags</h2>
                <div class="flex flex-col gap-3">
                    @forelse($tags as $tag)
                        <div class="flex items-center justify-between gap-2 rounded-xl bg-slate-800/80 px-4 py-3 border border-slate-700">
                            <form action="{{ route('admin.tags.update', $tag->id) }}" method="POST" class="flex-1 flex items-center gap-2">
                                @csrf
                                @method('PUT')
                                <input type="text" name="name" value="{{ $tag->name }}" required class="block w-full rounded-md border-slate-600 bg-slate-900 text-white shadow-sm focus:border-rose-500 focus:ring-rose-500 sm:text-sm">
                                <button type="submit" class="rounded bg-indigo-600/90 hover:bg-indigo-500 px-3 py-1.5 text-xs font-semibold text-white shadow-sm transition">
                                    Opslaan
                                </button>
                            </form>
                            
                            <form action="{{ route('admin.tags.destroy', $tag->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Weet je zeker dat je de tag {{ $tag->name }} wilt verwijderen?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded bg-rose-600/90 hover:bg-rose-500 px-3 py-1.5 text-xs font-semibold text-white shadow-sm transition">
                                    Verwijderen
                                </button>
                            </form>
                        </div>
                    @empty
                        <p class="text-slate-400 text-sm">Geen tags gevonden.</p>
                    @endforelse
                </div>
            </section>
        </div>
    </main>
</body>
</html>
