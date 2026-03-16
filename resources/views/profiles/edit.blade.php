<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DigiDate – Profiel bewerken</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 bg-gradient-to-br from-rose-900 via-slate-950 to-slate-900 text-slate-100">
    @include('layouts.navbar')

    <main class="mx-auto max-w-4xl px-4 py-8 space-y-8">
        <section class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-semibold text-rose-100">Mijn Profiel</h1>
                <p class="mt-2 text-sm text-slate-300">
                    Pas je gegevens aan, upload een foto en stel je privacyvoorkeuren in.
                </p>
            </div>
            <a href="{{ route('profiles.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-slate-800 border border-slate-700 px-4 py-2 text-sm font-medium text-slate-200 shadow-sm hover:bg-slate-700 hover:text-white transition">
                Terug naar Profielen
            </a>
        </section>
    
        @if(session('success'))
            <div class="rounded-xl bg-emerald-500/20 p-4 border border-emerald-500/40 text-emerald-200 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('profiles.update') }}" method="POST" enctype="multipart/form-data" class="grid gap-6 md:grid-cols-3">
            @csrf
            @method('PUT')

            <!-- Linker Kolom: Foto & Privacy -->
            <div class="space-y-6">
                <!-- Foto Upload -->
                <div class="rounded-2xl border border-slate-800/80 bg-slate-900/70 p-6">
                    <h2 class="font-semibold text-rose-100 mb-4">Profielfoto</h2>
                    
                    <div class="mb-4">
                        @if($user->profile_photo)
                            <img src="{{ route('profiles.photo', $user->id) }}" 
                                 onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($user->first_name ?? $user->name) }}&background=1e293b&color=fda4af&size=200';"
                                 alt="Jouw foto" class="h-32 w-32 object-cover rounded-full border-4 border-slate-800 mx-auto">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->first_name ?? $user->name) }}&background=1e293b&color=fda4af&size=200" 
                                 alt="Standaard Profielfoto" class="h-32 w-32 object-cover rounded-full border-4 border-slate-800 mx-auto">
                        @endif
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-300 mb-1">Nieuwe foto uploaden</label>
                        <input type="file" name="profile_photo" accept="image/*" class="block w-full text-xs text-slate-400 file:mr-3 file:rounded-full file:border-0 file:bg-rose-500/20 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-rose-200 hover:file:bg-rose-500/30 transition">
                        @error('profile_photo')
                            <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Privacy Settings -->
                <div class="rounded-2xl border border-slate-800/80 bg-slate-900/70 p-6">
                    <h2 class="font-semibold text-rose-100 mb-4">Privacy</h2>
                    <p class="text-xs text-slate-400 mb-4">Kies wat anderen mogen zien op jouw profiel.</p>

                    <div class="space-y-3">
                        <label class="flex items-center gap-3">
                            <input type="checkbox" name="bio_visible" value="1" {{ $user->bio_visible ? 'checked' : '' }} class="h-4 w-4 rounded border-slate-700 bg-slate-800 text-rose-500 focus:ring-rose-500 focus:ring-offset-slate-900">
                            <span class="text-sm text-slate-300">Toon biografie</span>
                        </label>
                        <label class="flex items-center gap-3">
                            <input type="checkbox" name="interests_visible" value="1" {{ $user->interests_visible ? 'checked' : '' }} class="h-4 w-4 rounded border-slate-700 bg-slate-800 text-rose-500 focus:ring-rose-500 focus:ring-offset-slate-900">
                            <span class="text-sm text-slate-300">Toon interesses</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Rechterkolom: Persoonlijke Info -->
            <div class="md:col-span-2 space-y-6">
                <!-- Basis Info -->
                <div class="rounded-2xl border border-slate-800/80 bg-slate-900/70 p-6">
                    <h2 class="font-semibold text-rose-100 mb-4">Persoonlijke Gegevens</h2>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-xs font-medium text-slate-300 mb-1">Voornaam</label>
                            <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}" class="w-full rounded-xl border border-slate-700 bg-slate-800/50 px-3 py-2 text-sm text-slate-100 focus:border-rose-500 focus:outline-none focus:ring-1 focus:ring-rose-500">
                            @error('first_name')
                                <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-300 mb-1">Achternaam</label>
                            <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}" class="w-full rounded-xl border border-slate-700 bg-slate-800/50 px-3 py-2 text-sm text-slate-100 focus:border-rose-500 focus:outline-none focus:ring-1 focus:ring-rose-500">
                            @error('last_name')
                                <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="block text-xs font-medium text-slate-300 mb-1">Bioscoop / Biografie</label>
                        <textarea name="biography" rows="4" class="w-full rounded-xl border border-slate-700 bg-slate-800/50 px-3 py-2 text-sm text-slate-100 focus:border-rose-500 focus:outline-none focus:ring-1 focus:ring-rose-500">{{ old('biography', $user->biography) }}</textarea>
                    </div>
                </div>

                <!-- Interesses / Tags -->
                <div class="rounded-2xl border border-slate-800/80 bg-slate-900/70 p-6">
                    <h2 class="font-semibold text-rose-100 mb-4">Interesses (Tags)</h2>
                    <p class="text-xs text-slate-400 mb-4">
                        Voeg interesses toe (gescheiden door komma's) om de matching te verbeteren. Bijv: reizen, sport, muziek.
                    </p>
                    
                    <div>
                        <input type="text" name="tags" value="{{ old('tags', implode(',', $userTags)) }}" placeholder="Eten, Wandelen, Gamen, ..." class="w-full rounded-xl border border-slate-700 bg-slate-800/50 px-3 py-2 text-sm text-slate-100 focus:border-rose-500 focus:outline-none focus:ring-1 focus:ring-rose-500">
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-4">
                    <button type="submit" class="rounded-xl bg-rose-500 px-6 py-2.5 text-sm font-semibold text-white shadow-xl shadow-rose-900/40 hover:bg-rose-400 transition focus:outline-none focus:ring-2 focus:ring-rose-300 focus:ring-offset-2 focus:ring-offset-slate-900">
                        Wijzigingen Opslaan
                    </button>
                </div>
            </div>
        </form>
    </main>
</body>
</html>
