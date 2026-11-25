<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DigiDate – Admin Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 bg-gradient-to-br from-rose-900 via-slate-950 to-slate-900 text-slate-100">
    @include('layouts.navbar')

    <main class="mx-auto max-w-6xl px-4 py-8 space-y-8">
        <section class="flex flex-col flex-row items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-semibold text-rose-100">Gebruikers Beheer</h1>
                <p class="mt-2 text-sm text-slate-300">
                    Beheer gebruikers, bans en meer.
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

        <section class="rounded-2xl border border-slate-800/80 bg-slate-900/70 p-6 shadow-xl">
            <h2 class="text-xl font-medium text-rose-100 mb-6">Alle Gebruikers</h2>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-300">
                    <thead class="text-xs uppercase bg-slate-800/50 text-slate-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">Naam</th>
                            <th scope="col" class="px-6 py-3">E-mail</th>
                            <th scope="col" class="px-6 py-3">Actief / Geband</th>
                            <th scope="col" class="px-6 py-3 text-right">Acties</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr class="border-b border-slate-800/50 hover:bg-slate-800/30">
                                <td class="px-6 py-4 font-medium text-white">{{ $user->first_name }} {{ $user->last_name }}</td>
                                <td class="px-6 py-4">{{ $user->email }}</td>
                                <td class="px-6 py-4">
                                    @if($user->trashed())
                                        <span class="inline-flex items-center gap-1 rounded-full bg-red-500/10 px-2 py-1 text-xs font-semibold text-red-400 border border-red-500/20">
                                            Geband
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-full bg-green-500/10 px-2 py-1 text-xs font-semibold text-green-400 border border-green-500/20">
                                            Actief
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @if($user->trashed())
                                        <form action="{{ route('admin.users.unban', $user->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            <button type="submit" class="rounded-lg bg-emerald-600/90 hover:bg-emerald-500 px-3 py-1.5 text-xs font-medium text-white shadow-sm transition">
                                                Unban
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.users.ban', $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Weet je zeker dat je deze gebruiker wilt bannen?');">
                                            @csrf
                                            <button type="submit" class="rounded-lg bg-rose-600/90 hover:bg-rose-500 px-3 py-1.5 text-xs font-medium text-white shadow-sm transition">
                                                Ban
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-slate-500">
                                    Geen gebruikers gevonden.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>
</html>
