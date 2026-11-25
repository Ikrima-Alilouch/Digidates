<header class="border-b border-slate-800/80 bg-slate-950/70 backdrop-blur">
    <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-4">
        <a href="{{ auth()->check() ? (auth()->user()-> role === 'admin' ? route('admin.dashboard') : route('user.dashboard')) : url('/') }}" class="flex items-center gap-2 text-sm font-semibold tracking-tight">
            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-rose-500/80 shadow-lg shadow-rose-900/40">
                <span class="text-lg">♥</span>
            </span>
            <span class="text-base text-rose-100">
                Digi<span class="text-rose-400">Date</span>
            </span>
        </a>

        @if (Route::has('login'))
            <nav class="flex items-center gap-3 text-xs font-medium">
                @auth
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="text-slate-200 hover:text-white transition {{ request()->routeIs('admin.dashboard') ? 'text-rose-400 font-bold' : '' }}">Gebruikers</a>
                        <a href="{{ route('admin.tags.index') }}" class="text-slate-200 hover:text-white transition {{ request()->routeIs('admin.tags.*') ? 'text-rose-400 font-bold' : '' }}">Tags</a>
                    @else
                        <a href="{{ route('profiles.index') }}" class="text-slate-200 hover:text-white transition {{ request()->routeIs('profiles.index') ? 'text-rose-400 font-bold' : '' }}">Ontdek</a>
                        <a href="{{ route('chat.index') }}" class="text-slate-200 hover:text-white transition {{ request()->routeIs('chat.*') ? 'text-rose-400 font-bold' : '' }}">Inbox @if(auth()->user()->messagesReceived()->where('is_read', false)->count() > 0)<span class="ml-1 inline-flex items-center justify-center w-4 h-4 text-[10px] font-bold text-white bg-rose-500 rounded-full">{{ auth()->user()->messagesReceived()->where('is_read', false)->count() }}</span>@endif</a>
                        <a href="{{ route('profiles.edit') }}" class="text-slate-200 hover:text-white transition {{ request()->routeIs('profiles.edit') ? 'text-rose-400 font-bold' : '' }}">Profiel</a>
                    @endif

                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button
                            type="submit"
                            class="inline-flex items-center gap-1 rounded-full border border-slate-700/80 bg-slate-900/80 px-4 py-1.5 text-xs font-semibold text-slate-100 hover:bg-slate-800 transition"
                        >
                            Uitloggen
                        </button>
                    </form>
                @else
                    <a
                        href="{{ route('login') }}"
                        class="inline-flex items-center gap-1 rounded-full border border-white/10 bg-white/5 px-4 py-1.5 text-slate-50 shadow-sm hover:bg-white/10 transition"
                    >
                        Log in
                    </a>
                    @if (Route::has('register'))
                        <a
                            href="{{ route('register') }}"
                            class="inline-flex items-center gap-1 rounded-full border border-rose-400/60 bg-rose-500/90 px-4 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-rose-400 transition"
                        >
                            Registreren
                        </a>
                    @endif
                @endauth
            </nav>
        @endif
    </div>
</header>

