<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Chat met {{ $match->first_name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#11090a] text-white antialiased absolute inset-0 flex flex-col overflow-hidden">

{{-- Chat Header --}}
<header class="flex items-center justify-between w-full bg-slate-950 border-b border-slate-800/80 px-4 py-3 z-20 shrink-0 shadow-md">
    <div class="flex items-center gap-4">
        <a href="{{ route('chat.index') }}" class="text-rose-400 hover:text-white transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        
        <a href="{{ route('profiles.show', $match->id) }}" class="flex items-center gap-3">
            @if($match->profile_photo && $match->photo_visible)
                <img src="{{ route('profiles.photo', $match->id) }}" alt="{{ $match->first_name }}" class="w-10 h-10 rounded-full object-cover border border-slate-700">
            @else
                <img src="https://ui-avatars.com/api/?name={{ urlencode($match->first_name ?? $match->name) }}&background=1e293b&color=fda4af" class="w-10 h-10 rounded-full object-cover">
            @endif
            <div>
                <h2 class="font-bold text-sm tracking-wide">{{ $match->first_name }}</h2>
                <p class="text-[10px] text-green-400 font-medium">Online</p>
            </div>
        </a>
    </div>

    {{-- Unmatch Button --}}
    <form action="{{ route('match.unmatch', $match->id) }}" method="POST" onsubmit="return confirm('Weet je zeker dat je deze match wilt verbreken? Je kunt dan niet meer met elkaar chatten.');">
        @csrf
        <button type="submit" class="text-slate-400 hover:text-rose-500 transition tooltip" title="Match verbreken">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M3.28 2.22a.75.75 0 00-1.06 1.06l14.5 14.5a.75.75 0 101.06-1.06l-2.03-2.03C17.6 13.3 18 11.23 18 9c0-4.97-4.03-9-9-9-2.02 0-3.87.67-5.35 1.8l-1.37-1.37zM4.68 5.74A8.97 8.97 0 003 9c0 4.97 4.03 9 9 9 1.86 0 3.58-.57 5.02-1.54l-12.34-12.34z" clip-rule="evenodd" />
            </svg>
        </button>
    </form>
</header>

{{-- Messages Area --}}
<main class="flex-1 overflow-y-auto pb-4 pt-4 px-4 flex flex-col gap-3 scroll-smooth h-full" id="messages-container">
    <div class="text-center my-4">
        <p class="text-xs text-slate-500 bg-slate-900/50 inline-block px-3 py-1 rounded-full">Je bent gematcht met {{ $match->first_name }}</p>
    </div>

    @foreach($messages as $msg)
        @if($msg->sender_id === auth()->id())
            {{-- Sent Message --}}
            <div class="flex justify-end mb-1">
                <div class="max-w-[75%] rounded-2xl rounded-tr-sm bg-rose-500 text-white px-4 py-2 text-sm shadow-md">
                    {{ $msg->message }}
                    <div class="text-[9px] text-rose-200 text-right mt-1">{{ $msg->created_at->format('H:i') }}</div>
                </div>
            </div>
        @else
            {{-- Received Message --}}
            <div class="flex justify-start mb-1">
                <div class="max-w-[75%] rounded-2xl rounded-tl-sm bg-slate-800 text-slate-100 px-4 py-2 text-sm shadow-sm border border-slate-700">
                    {{ $msg->message }}
                    <div class="text-[9px] text-slate-400 text-right mt-1">{{ $msg->created_at->format('H:i') }}</div>
                </div>
            </div>
        @endif
    @endforeach
</main>

{{-- Input Area --}}
<footer class="bg-slate-950 border-t border-slate-800/80 p-3 shrink-0 pb-safe z-20 sticky bottom-0 w-full">
    <form action="{{ route('chat.store', $match->id) }}" method="POST" class="flex items-center gap-2 max-w-md mx-auto">
        @csrf
        <input type="text" name="message" placeholder="Typ een bericht..." class="flex-1 rounded-full bg-slate-900 border border-slate-700 px-4 py-2.5 text-sm text-white focus:outline-none focus:ring-1 focus:ring-rose-500 focus:border-rose-500" required autocomplete="off" autofocus>
        <button type="submit" class="h-10 w-10 flex items-center justify-center rounded-full bg-rose-500 text-white shadow-md hover:bg-rose-600 transition shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
            </svg>
        </button>
    </form>
</footer>

<script>
    // Auto-scroll to bottom
    const container = document.getElementById('messages-container');
    container.scrollTop = container.scrollHeight;
</script>

</body>
</html>
