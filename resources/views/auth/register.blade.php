<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DigiDate – Registreren</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 bg-gradient-to-br from-rose-900 via-slate-950 to-slate-900 text-slate-100">
    @include('layouts.navbar')
    <div class="w-full max-w-3xl mx-auto mt-10 px-4">
            <div class="mb-6 text-center">
                <h1 class="text-3xl font-semibold tracking-tight text-rose-100">
                    Maak je Digi<span class="text-rose-400">Date</span> account
                </h1>
                <p class="mt-2 text-sm text-slate-300">
                    Vul je gegevens in om te starten met daten. Je moet minimaal 18 jaar zijn.
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
                action="{{ route('register.store') }}"
                enctype="multipart/form-data"
                class="rounded-2xl bg-slate-900/80 border border-slate-800/80 shadow-2xl shadow-black/40 backdrop-blur px-6 py-6 space-y-5"
            >
                @csrf

                <div class="grid gap-4 md:grid-cols-3">
                    <div class="space-y-1.5 md:col-span-1">
                        <label for="first_name" class="block text-xs font-medium text-slate-200">
                            Voornaam
                        </label>
                        <input
                            id="first_name"
                            name="first_name"
                            type="text"
                            value="{{ old('first_name') }}"
                            required
                            class="block w-full rounded-xl border border-slate-700/80 bg-slate-900/70 px-3 py-2 text-sm text-slate-100 placeholder:text-slate-500 shadow-inner focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-rose-500/70 focus-visible:border-rose-400/60 transition"
                        >
                    </div>
                    <div class="space-y-1.5 md:col-span-1">
                        <label for="middle_name" class="block text-xs font-medium text-slate-200">
                            Tussenvoegsel
                        </label>
                        <input
                            id="middle_name"
                            name="middle_name"
                            type="text"
                            value="{{ old('middle_name') }}"
                            class="block w-full rounded-xl border border-slate-700/80 bg-slate-900/70 px-3 py-2 text-sm text-slate-100 placeholder:text-slate-500 shadow-inner focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-rose-500/70 focus-visible:border-rose-400/60 transition"
                            placeholder="(optioneel)"
                        >
                    </div>
                    <div class="space-y-1.5 md:col-span-1">
                        <label for="last_name" class="block text-xs font-medium text-slate-200">
                            Achternaam
                        </label>
                        <input
                            id="last_name"
                            name="last_name"
                            type="text"
                            value="{{ old('last_name') }}"
                            required
                            class="block w-full rounded-xl border border-slate-700/80 bg-slate-900/70 px-3 py-2 text-sm text-slate-100 placeholder:text-slate-500 shadow-inner focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-rose-500/70 focus-visible:border-rose-400/60 transition"
                        >
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-3">
                    <div class="space-y-1.5">
                        <label for="country" class="block text-xs font-medium text-slate-200">
                            Provincie
                        </label>
                        <input
                            id="country"
                            name="country"
                            type="text"
                            value="{{ old('country') }}"
                            required
                            class="block w-full rounded-xl border border-slate-700/80 bg-slate-900/70 px-3 py-2 text-sm text-slate-100 placeholder:text-slate-500 shadow-inner focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-rose-500/70 focus-visible:border-rose-400/60 transition"
                            placeholder="Bijv. Noord-Holland"
                        >
                    </div>
                    <div class="space-y-1.5">
                        <label for="location" class="block text-xs font-medium text-slate-200">
                            Stad
                        </label>
                        <input
                            id="location"
                            name="location"
                            type="text"
                            value="{{ old('location') }}"
                            required
                            class="block w-full rounded-xl border border-slate-700/80 bg-slate-900/70 px-3 py-2 text-sm text-slate-100 placeholder:text-slate-500 shadow-inner focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-rose-500/70 focus-visible:border-rose-400/60 transition"
                            placeholder="Bijv. Amsterdam"
                        >
                    </div>
                    <div class="space-y-1.5">
                        <label for="age" class="block text-xs font-medium text-slate-200">
                            Leeftijd
                        </label>
                        <input
                            id="age"
                            name="age"
                            type="number"
                            min="18"
                            value="{{ old('age') }}"
                            required
                            class="block w-full rounded-xl border border-slate-700/80 bg-slate-900/70 px-3 py-2 text-sm text-slate-100 placeholder:text-slate-500 shadow-inner focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-rose-500/70 focus-visible:border-rose-400/60 transition"
                        >
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div class="space-y-1.5">
                        <label for="email" class="block text-xs font-medium text-slate-200">
                            E-mailadres
                        </label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email') }}"
                            required
                            class="block w-full rounded-xl border border-slate-700/80 bg-slate-900/70 px-3 py-2 text-sm text-slate-100 placeholder:text-slate-500 shadow-inner focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-rose-500/70 focus-visible:border-rose-400/60 transition"
                            placeholder="jij@digidate.nl"
                        >
                    </div>
                    <div class="space-y-1.5">
                        <label for="gender" class="block text-xs font-medium text-slate-200">
                            Geslacht
                        </label>
                        <select
                            id="gender"
                            name="gender"
                            required
                            class="block w-full rounded-xl border border-slate-700/80 bg-slate-900/70 px-3 py-2 text-sm text-slate-100 shadow-inner focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-rose-500/70 focus-visible:border-rose-400/60 transition"
                        >
                            <option value="" disabled {{ old('gender') ? '' : 'selected' }}>Kies je geslacht</option>
                            <option value="man" {{ old('gender') === 'man' ? 'selected' : '' }}>Man</option>
                            <option value="vrouw" {{ old('gender') === 'vrouw' ? 'selected' : '' }}>Vrouw</option>
                            <option value="anders" {{ old('gender') === 'anders' ? 'selected' : '' }}>Anders / zeg ik liever niet</option>
                        </select>
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div class="space-y-1.5">
                        <label for="password" class="block text-xs font-medium text-slate-200">
                            Wachtwoord (min. 8 tekens)
                        </label>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            required
                            class="block w-full rounded-xl border border-slate-700/80 bg-slate-900/70 px-3 py-2 text-sm text-slate-100 placeholder:text-slate-500 shadow-inner focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-rose-500/70 focus-visible:border-rose-400/60 transition"
                            placeholder="••••••••"
                        >
                    </div>
                    <div class="space-y-1.5">
                        <label for="password_confirmation" class="block text-xs font-medium text-slate-200">
                            Herhaal wachtwoord
                        </label>
                        <input
                            id="password_confirmation"
                            name="password_confirmation"
                            type="password"
                            required
                            class="block w-full rounded-xl border border-slate-700/80 bg-slate-900/70 px-3 py-2 text-sm text-slate-100 placeholder:text-slate-500 shadow-inner focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-rose-500/70 focus-visible:border-rose-400/60 transition"
                            placeholder="Nogmaals hetzelfde wachtwoord"
                        >
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div class="space-y-1.5">
                        <label for="profile_photo" class="block text-xs font-medium text-slate-200">
                            Profielfoto
                        </label>
                        <input
                            id="profile_photo"
                            name="profile_photo"
                            type="file"
                            accept="image/*"
                            required
                            class="block w-full text-xs text-slate-200 file:mr-3 file:rounded-lg file:border-0 file:bg-rose-500 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-white hover:file:bg-rose-400"
                        >
                        <p class="mt-1 text-[10px] text-slate-400">
                            Max 2MB. Zorg dat je gezicht duidelijk zichtbaar is.
                        </p>
                    </div>

                    <div class="space-y-1.5">
                        <span class="block text-xs font-medium text-slate-200">
                            Interesses (tags)
                        </span>
                        <div class="grid grid-cols-2 gap-1.5 max-h-32 overflow-y-auto rounded-xl border border-slate-800/80 bg-slate-900/60 p-2 text-[11px]">
                            @forelse ($tags as $tag)
                                <label class="inline-flex items-center gap-2 text-slate-200/90">
                                    <input
                                        type="checkbox"
                                        name="tags[]"
                                        value="{{ $tag->id }}"
                                        class="h-3.5 w-3.5 rounded border-slate-600 bg-slate-900 text-rose-500 focus:ring-rose-500/70"
                                        {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }}
                                    >
                                    <span>{{ $tag->name }}</span>
                                </label>
                            @empty
                                <p class="col-span-2 text-[10px] text-slate-400">
                                    Er zijn nog geen tags aangemaakt. Voeg later interesses toe in je profiel.
                                </p>
                            @endforelse
                        </div>
                        <p class="mt-1 text-[10px] text-slate-400">
                            Kies wat bij jou past. Dit helpt bij het vinden van betere matches.
                        </p>
                    </div>
                </div>

                <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <button
                        type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-2xl bg-rose-500 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-rose-900/50 hover:bg-rose-400 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-offset-slate-950 focus-visible:ring-rose-400 transition md:w-auto w-full"
                    >
                        Account aanmaken
                        <span class="text-base leading-none">♥</span>
                    </button>

                    <p class="text-[11px] text-slate-300/80 md:text-right">
                        Heb je al een account?
                        <a href="{{ route('login') }}" class="font-medium text-rose-300 hover:text-rose-200 underline underline-offset-4">
                            Log in
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </body>
    </html>

