<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DigiDate – Inbox</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#11090a] text-white antialiased">

@include('layouts.navbar')

<main class="mx-auto max-w-md bg-[#11090a] min-h-screen flex flex-col p-4">
    <h1 class="text-2xl font-bold mb-6 text-white">Inbox</h1>

    @if($matches->isEmpty())
        <div class="flex flex-col items-center justify-center py-20 text-center">
            <div class="h-24 w-24 rounded-full bg-slate-900/50 flex items-center justify-center mb-4">
                <svg class="w-12 h-12 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
            </div>
            <h2 class="text-xl font-semibold text-slate-300">Geen matches nog</h2>
            <p class="text-slate-500 mt-2 text-sm">Like profielen om in contact te komen met anderen.</p>
            <a href="{{ route('profiles.index') }}" class="mt-6 rounded-full bg-rose-500 px-6 py-2 text-sm font-semibold text-white shadow-lg shadow-rose-500/30 hover:bg-rose-400">Ontdek Mensen</a>
        </div>
    @else
        <div class="flex flex-col gap-3">
            @foreach($matches as $match)
                <a href="{{ route('chat.show', $match->id) }}" class="flex items-center gap-4 bg-slate-900/40 hover:bg-slate-800/80 p-3 rounded-2xl transition border border-slate-800/50">
                    <div class="relative">
                        @if($match->profile_photo && $match->photo_visible)
                            <img src="{{ route('profiles.photo', $match->id) }}" alt="{{ $match->first_name }}" class="w-14 h-14 rounded-full object-cover border border-slate-700">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($match->first_name ?? $match->name) }}&background=1e293b&color=fda4af" class="w-14 h-14 rounded-full object-cover">
                        @endif
                        <div class="absolute bottom-0 right-0 w-3.5 h-3.5 bg-green-500 border-2 border-[#11090a] rounded-full"></div>
                    </div>
                    
                    <div class="flex-1 overflow-hidden">
                        <div class="flex justify-between items-center mb-1">
                            <h3 class="font-bold text-slate-200">{{ $match->first_name }}</h3>
                            @if($match->latest_message)
                                <span class="text-[10px] text-slate-500">{{ $match->latest_message->created_at->diffForHumans(null, true, true) }}</span>
                            @endif
                        </div>
                        <p class="text-sm text-slate-400 truncate {{ $match->unread_count > 0 ? 'font-semibold text-white' : '' }}">
                            @if($match->latest_message)
                                @if($match->latest_message->sender_id === auth()->id())
                                    <span class="text-slate-500">Jij: </span>
                                @endif
                                {{ $match->latest_message->message }}
                            @else
                                <span class="italic text-rose-300 text-xs">Jullie zijn gematcht! Zeg hallo.</span>
                            @endif
                        </p>
                    </div>

                    @if($match->unread_count > 0)
                        <div class="w-5 h-5 bg-rose-500 text-white text-[10px] font-bold flex items-center justify-center rounded-full">
                            {{ $match->unread_count }}
                        </div>
                    @endif
                </a>
            @endforeach
        </div>
    @endif
</main>

</body>
</html>
