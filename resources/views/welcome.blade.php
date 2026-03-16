<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>DigiDate</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-slate-950 text-slate-50">
        <div class="relative flex min-h-screen flex-col overflow-hidden">
            <div class="pointer-events-none absolute inset-0 -z-10 bg-gradient-to-br from-rose-900 via-slate-950 to-fuchsia-900 opacity-80"></div>
            <div class="pointer-events-none absolute inset-0 -z-10">
                <div class="absolute -top-10 -left-10 h-64 w-64 rounded-full bg-rose-500/20 blur-3xl"></div>
                <div class="absolute top-40 -right-8 h-72 w-72 rounded-full bg-fuchsia-500/20 blur-3xl"></div>
                <div class="absolute bottom-0 left-1/3 h-40 w-40 rounded-full bg-pink-500/10 blur-3xl"></div>
            </div>

            @include('layouts.navbar')

            <main class="relative z-10 mx-auto flex w-full max-w-6xl flex-1 flex-col items-center justify-center px-4 py-10 lg:flex-row lg:gap-10">
                <section class="w-full max-w-xl space-y-6 lg:space-y-7">
                    <div class="inline-flex items-center gap-2 rounded-full bg-black/30 px-3 py-1 text-[11px] font-medium text-rose-100 ring-1 ring-white/10 shadow-sm">
                        <span class="text-xs">♥</span>
                        <span>Vind echte connecties, niet alleen profielfoto’s</span>
                    </div>

                    <div class="space-y-4">
                        <h1 class="text-4xl font-semibold tracking-tight sm:text-5xl lg:text-6xl">
                            De digitale plek<br>
                            waar harten <span class="text-rose-300">matchen</span>.
                        </h1>
                        <p class="max-w-md text-sm text-slate-200/80 sm:text-base">
                            DigiDate brengt serieuze singles samen met slimme matching
                            op basis van interesses, locatie en wat jij écht belangrijk vindt.
                        </p>
                    </div>

                    <div class="flex flex-col gap-3 text-sm sm:flex-row sm:items-center">
                        <a
                            href="{{ route('login') }}"
                            class="inline-flex flex-1 items-center justify-center gap-2 rounded-2xl bg-rose-500 px-5 py-3 text-sm font-semibold text-white shadow-xl shadow-rose-900/50 hover:bg-rose-400 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-rose-300 focus-visible:ring-offset-2 focus-visible:ring-offset-slate-950 transition"
                        >
                            Start met daten
                            <span class="text-base leading-none">♥</span>
                        </a>
                        <div class="flex-1 text-[11px] text-slate-200/80">
                            <p class="font-medium">Veilig, modern en privacy-proof.</p>
                            <p>Log in om je matches, berichten en likes te zien.</p>
                        </div>
                    </div>

                    <dl class="grid grid-cols-3 gap-4 text-[11px] text-slate-200/80">
                        <div class="space-y-1 rounded-2xl bg-black/25 px-3 py-2 ring-1 ring-white/5">
                            <dt class="flex items-center gap-1 text-[10px] uppercase tracking-wide text-rose-200/80">
                                <span class="text-xs">♥</span> Matches
                            </dt>
                            <dd class="text-lg font-semibold text-rose-100">Slimme swipes</dd>
                        </div>
                        <div class="space-y-1 rounded-2xl bg-black/25 px-3 py-2 ring-1 ring-white/5">
                            <dt class="flex items-center gap-1 text-[10px] uppercase tracking-wide text-rose-200/80">
                                <span class="text-xs">♥</span> Chat
                            </dt>
                            <dd class="text-lg font-semibold text-rose-100">Veilige gesprekken</dd>
                        </div>
                        <div class="space-y-1 rounded-2xl bg-black/25 px-3 py-2 ring-1 ring-white/5">
                            <dt class="flex items-center gap-1 text-[10px] uppercase tracking-wide text-rose-200/80">
                                <span class="text-xs">♥</span> Controle
                            </dt>
                            <dd class="text-lg font-semibold text-rose-100">Banned users geblokt</dd>
                        </div>
                    </dl>
                </section>

                <section class="mt-10 w-full max-w-md lg:mt-0">
                    <div class="relative mx-auto max-w-sm">
                        <div class="absolute -top-10 -right-8 h-24 w-24 rotate-6 rounded-full bg-pink-500/40 blur-2xl"></div>
                        <div class="absolute -bottom-10 -left-10 h-28 w-28 -rotate-6 rounded-full bg-rose-600/40 blur-2xl"></div>

                        <div class="relative overflow-hidden rounded-3xl border border-white/10 bg-slate-950/80 px-5 py-6 shadow-2xl shadow-black/50 backdrop-blur">
                            <div class="mb-4 flex items-center justify-between text-xs text-slate-200/80">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-rose-500/80 text-base shadow-md shadow-rose-900/40">
                                        ♥
                                    </span>
                                    <div>
                                        <p class="font-semibold text-rose-100">Word lid van DigiDate</p>
                                        <p class="text-[10px] text-slate-300/80">Dé datingsite voor echte connecties</p>
                                    </div>
                                </div>
                                <span class="rounded-full bg-black/40 px-3 py-1 text-[10px] text-rose-100 ring-1 ring-white/10">
                                    Gratis & veilig aanmelden
                                </span>
                            </div>

                            <div class="flex flex-col items-center justify-center py-6">
                                <svg class="h-20 w-20 mb-4 text-rose-400" fill="none" viewBox="0 0 48 48">
                                    <circle cx="24" cy="24" r="24" fill="currentColor" fill-opacity="0.13"/>
                                    <text x="24" y="24" text-anchor="middle" font-size="28" dy="0.3em" fill="currentColor" fill-opacity="0.7">♥</text>
                                </svg>
                                <p class="text-center text-base font-semibold text-rose-100 mb-2">Bekijk profielen, ontmoet nieuwe mensen en vind een match!</p>
                                <ul class="mb-4 text-xs text-slate-200/80 space-y-1">
                                    <li>• Slimme matching op interesses en locatie</li>
                                    <li>• Privacy & veiligheid staan voorop</li>
                                    <li>• Alleen échte profielen, geen neppers</li>
                                </ul>
                                <div class="flex flex-col items-center gap-2">
                                    <a
                                        href="{{ route('register') }}"
                                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-rose-500 px-6 py-3 text-sm font-semibold text-white shadow-xl shadow-rose-900/40 hover:bg-rose-400 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-rose-300 focus-visible:ring-offset-2 transition w-full"
                                    >
                                        Gratis registreren
                                        <span class="text-base leading-none">♥</span>
                                    </a>
                                    <a
                                        href="{{ route('login') }}"
                                        class="text-[11px] text-slate-200/90 underline underline-offset-4 hover:text-rose-200"
                                    >
                                        Ik heb al een account – inloggen
                                    </a>
                                </div>
                            </div>

                            <p class="mt-4 text-[11px] text-slate-300/80 text-center">
                                Maak gratis een account aan om de community te ontdekken.<br>
                                Ervaar zelf hoe makkelijk daten online kan zijn!
                            </p>
                        </div>

                
                    </div>
                </section>
            </main>

            <footer class="relative z-10 border-t border-white/5 bg-black/30 text-[10px] text-slate-300/80">
                <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-3">
                    <p>DigiDate &copy; {{ date('Y') }}</p>
                    <p>Gemaakt met Laravel & Tailwind CSS</p>
                </div>
            </footer>
        </div>
    </body>
</html>