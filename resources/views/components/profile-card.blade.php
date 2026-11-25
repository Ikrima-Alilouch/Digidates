<a href="{{ route('profiles.show', $user->id) }}" class="block flex flex-col overflow-hidden rounded-2xl border border-slate-800/80 bg-slate-900/70 shadow-xl transition-transform hover:-translate-y-1 hover:shadow-2xl hover:shadow-rose-900/20">
    {{-- Photo --}}
    <div class="relative h-48 w-full bg-slate-800">
        @if($user->photo_visible && $user->profile_photo)
            <img src="{{ route('profiles.photo', $user->id) }}" 
                 onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($user->first_name ?? $user->name) }}&background=1e293b&color=fda4af&size=400';"
                 alt="Profielfoto van {{ $user->first_name ?? $user->name }}" class="h-full w-full object-cover">
        @else
            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->first_name ?? $user->name) }}&background=1e293b&color=fda4af&size=400" 
                 alt="Standaard Profielfoto" class="h-full w-full object-cover">
        @endif
    </div>

    {{-- Info --}}
    <div class="flex flex-1 flex-col p-5">
        <h3 class="text-lg font-semibold text-rose-100">
            {{ $user->first_name ?? $user->name }} {{ $user->last_name ?? '' }}
            @if($user->birthdate)
                <span class="text-sm font-normal text-slate-400">, {{ \Carbon\Carbon::parse($user->birthdate)->age }}</span>
            @endif
        </h3>
        
        <div class="mb-4 mt-1 flex items-center gap-2 text-xs text-slate-400">
            @if($user->location)
                <span class="inline-flex items-center gap-1">
                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    {{ $user->location }}
                </span>
            @endif
        </div>

        {{-- Bio --}}
        @if($user->bio_visible && $user->biography)
            <p class="mb-4 text-sm text-slate-300 line-clamp-3">
                {{ $user->biography }}
            </p>
        @elseif(!$user->bio_visible)
            <p class="mb-4 text-xs italic text-slate-500">
                Bio is verborgen
            </p>
        @endif

        {{-- Interests --}}
        <div class="mt-auto">
            @if($user->interests_visible && $user->tags->isNotEmpty())
                <div class="flex flex-wrap gap-1.5">
                    @foreach($user->tags->take(4) as $tag)
                        <span class="rounded-full bg-rose-500/10 px-2 py-0.5 text-[10px] font-medium text-rose-200 border border-rose-400/20">
                            {{ $tag->name }}
                        </span>
                    @endforeach
                    @if($user->tags->count() > 4)
                        <span class="rounded-full bg-slate-800 px-2 py-0.5 text-[10px] font-medium text-slate-400">
                            +{{ $user->tags->count() - 4 }}
                        </span>
                    @endif
                </div>
            @elseif(!$user->interests_visible)
                <p class="text-xs italic text-slate-500">Interesses verborgen</p>
            @endif
        </div>
    </div>
</a>
