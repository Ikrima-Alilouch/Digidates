<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DigiDate – {{ $user->first_name ?? $user->name }}'s Profiel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#11090a] text-white antialiased">

<main class="mx-auto max-w-md bg-[#11090a] min-h-screen flex flex-col relative pb-20">
    
    {{-- Back Button Overlay --}}
    <a href="{{ route('profiles.index') }}" class="absolute top-4 left-4 z-10 flex h-10 w-10 items-center justify-center rounded-full bg-white text-black shadow-md hover:bg-slate-200 transition">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
        </svg>
    </a>

    {{-- Profile Image --}}
    <div class="relative w-full aspect-[4/5] bg-slate-800 rounded-b-[2rem] overflow-hidden">
        @if($user->photo_visible && $user->profile_photo)
            <img src="{{ route('profiles.photo', $user->id) }}" 
                 alt="Profielfoto van {{ $user->first_name ?? $user->name }}" 
                 class="h-full w-full object-cover">
        @else
            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->first_name ?? $user->name) }}&background=1e293b&color=fda4af&size=800" 
                 alt="Standaard Profielfoto" 
                 class="h-full w-full object-cover">
        @endif
        
        {{-- Gradient Overlay for text readability at the bottom of the image if needed --}}
        <div class="absolute inset-x-0 bottom-0 h-32 bg-gradient-to-t from-[#11090a] to-transparent"></div>
    </div>

    {{-- Info Section --}}
    <div class="px-6 -mt-8 relative z-10 w-full">
        <div class="flex items-start justify-between">
            <div>
                <h1 class="text-3xl font-extrabold flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.6)]"></span>
                    {{ $user->first_name ?? $user->name }}
                    {{-- Verified Badge --}}
                    <svg class="w-6 h-6 text-blue-500" viewBox="0 0 24 24" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.603 3.799A4.49 4.49 0 0112 2.25c1.357 0 2.573.6 3.397 1.549a4.49 4.49 0 013.498 1.307 4.491 4.491 0 011.307 3.497A4.49 4.49 0 0121.75 12a4.49 4.49 0 01-1.549 3.397 4.491 4.491 0 01-1.307 3.497 4.491 4.491 0 01-3.497 1.307A4.49 4.49 0 0112 21.75a4.49 4.49 0 01-3.397-1.549 4.49 4.49 0 01-3.498-1.306 4.491 4.491 0 01-1.307-3.498A4.49 4.49 0 012.25 12c0-1.357.6-2.573 1.549-3.397a4.49 4.49 0 011.307-3.497 4.49 4.49 0 013.497-1.307zm7.007 6.387a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" />
                    </svg>
                </h1>
                
                <p class="text-rose-100/90 text-lg font-medium mt-1">
                    @if($user->birthdate)
                        {{ \Carbon\Carbon::parse($user->birthdate)->age }} • 
                    @endif
                    {{ ucfirst($user->gender ?? 'Onbekend') }}
                </p>
                
                @if($user->location)
                    <p class="text-rose-100/80 text-sm flex items-center gap-1 mt-1 font-medium">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        </svg>
                        {{ $user->location }}
                    </p>
                @endif
            </div>

            @if(auth()->id() !== $user->id)
                <div class="flex items-center gap-2">
                    @if($isMatch)
                        <a href="{{ route('chat.show', $user->id) }}" class="flex items-center justify-center h-12 w-12 rounded-full bg-rose-500 text-white shadow-lg shadow-rose-500/40 hover:bg-rose-600 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                        </a>
                    @elseif($hasLiked)
                        <div class="flex items-center justify-center h-12 rounded-full bg-green-500/20 px-4 text-green-400 font-semibold border border-green-500/30">
                            Geliket
                        </div>
                    @else
                        <form method="POST" action="{{ route('profiles.like', $user->id) }}">
                            @csrf
                            <button type="submit" class="flex items-center justify-center h-12 w-12 rounded-full bg-slate-800 text-rose-500 shadow-md border border-rose-500/30 hover:bg-rose-500 hover:text-white hover:border-rose-500 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                </svg>
                            </button>
                        </form>
                    @endif
                </div>
            @endif
        </div>
    </div>

    {{-- Basics Section --}}
    <div class="px-6 mt-8">
        <h2 class="text-xl font-bold text-white mb-4">Basics</h2>
        
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-gray-900/50 rounded-xl p-3 border border-gray-800/50">
                <p class="text-xs text-slate-400 mb-1">Volledige Naam</p>
                <p class="text-sm font-medium">{{ $user->first_name }} {{ $user->middle_name }} {{ $user->last_name }}</p>
            </div>
            @if($user->country)
                <div class="bg-gray-900/50 rounded-xl p-3 border border-gray-800/50">
                    <p class="text-xs text-slate-400 mb-1">Land</p>
                    <p class="text-sm font-medium">{{ $user->country }}</p>
                </div>
            @endif
        </div>
        
        @if($user->bio_visible && $user->biography)
            <div class="mt-4 bg-gray-900/50 rounded-xl p-4 border border-gray-800/50">
                <p class="text-xs text-slate-400 mb-2">Biografie</p>
                <p class="text-sm leading-relaxed text-slate-300">{{ $user->biography }}</p>
            </div>
        @elseif(!$user->bio_visible)
            <div class="mt-4 bg-gray-900/50 rounded-xl p-4 border border-gray-800/50">
                <p class="text-sm italic text-slate-500">Biografie is verborgen</p>
            </div>
        @endif

        @if($user->interests_visible && $user->tags->isNotEmpty())
            <div class="mt-6">
                <h3 class="text-lg font-bold text-white mb-3">Interesses</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($user->tags as $tag)
                        <span class="rounded-full bg-slate-800 px-3 py-1.5 text-xs font-semibold text-rose-200 border border-slate-700">
                            {{ $tag->name }}
                        </span>
                    @endforeach
                </div>
            </div>
        @elseif(!$user->interests_visible)
            <div class="mt-6">
                <h3 class="text-lg font-bold text-white mb-3">Interesses</h3>
                <p class="text-sm text-slate-500 italic">Interesses verborgen</p>
            </div>
        @endif
    </div>

</main>

</body>
</html>
