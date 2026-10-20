<nav
    class="border-b border-slate-100 bg-white text-slate-700 shadow-sm"
    aria-label="Hoofdnavigatie"
>
    <div class="grid grid-cols-1 items-center gap-4 px-6 py-2 lg:grid-cols-[1fr_auto_1fr]">
        <a
            href="{{ route('home') }}"
            class="justify-self-start rounded-xl focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-teal-600"
            aria-label="FietsFix home"
        >
            <x-application-logo class="h-20 w-20 sm:h-24 sm:w-24" />
        </a>
        <div class="flex flex-wrap items-center justify-center gap-2">
            @foreach ([
                'home' => 'Home',
                'winkel.index' => 'FietsFix Webshop',
                'merken' => 'Merken',
                'fietsonderhoud' => 'Fietsonderhoud',
                'contact' => 'Contact'
            ] as $routeName => $label)
                @if ($routeName === 'winkel.index')
                    @include('winkel.menu')
                @else
                <a
                    href="{{ route($routeName) }}"
                    @if(request()->routeIs($routeName))
                        aria-current="page"
                    @endif
                    class="ff-nav-link {{ request()->routeIs($routeName) ? 'ff-nav-active' : 'ff-nav-inactive' }}"
                >
                    {{ $label }}
                </a>
                @endif
            @endforeach
        </div>
        <div class="flex flex wrap items-center justify-end gap-2">
            @unless (auth()->check() && auth()->user()->is_personeel)
                {{-- Tel alle aantallen in de sessie op voor de teller naast het mandje. --}}
                <a href="{{ route('winkelmand.index') }}" class="ff-nav-link {{ request()->routeIs('winkelmand.*', 'afrekenen.*') ? 'ff-nav-active' : 'ff-nav-inactive' }}">
                    Winkelmandje ({{ array_sum(session('winkelmand', [])) }})
                </a>
            @endunless
            @guest
                <a
                    href="{{ route('login') }}"
                    @if(request()->routeIs('login'))
                        aria-current="page"
                    @endif
                    class="ff-nav-link {{ request()->routeIs('login') ? 'ff-nav-active' : 'ff-nav-inactive' }}"
                >
                    Inloggen
                </a>
                <a
                    href="{{ route('register') }}"
                    @if(request()->routeIs('register'))
                        aria-current="page"
                    @endif
                    class="ff-nav-primary"
                >
                    Registreren
                </a>
            @else
                @if (auth()->user()->is_personeel)
                    <a href="{{ route('personeel.index') }}" class="ff-nav-link {{ request()->routeIs('personeel.*') ? 'ff-nav-active' : 'ff-nav-inactive' }}">
                        Personeelsbeheer
                    </a>
                @endif
                @unless (auth()->user()->is_personeel)
                    <a href="{{ route('bestellingen.index') }}" class="ff-nav-link {{ request()->routeIs('bestellingen.*') ? 'ff-nav-active' : 'ff-nav-inactive' }}">
                        Mijn bestellingen
                    </a>
                    <a
                        href="{{ route('dashboard') }}"
                        @if(request()->routeIs('dashboard', 'afspraken.*'))
                            aria-current="page"
                        @endif
                        class="ff-nav-primary"
                    >
                        Afspraak inzien
                    </a>
                @endunless
                <a
                    href="{{ route('profile.edit') }}"
                    @if(request()->routeIs('profile.*'))
                        aria-current="page"
                    @endif
                    class="ff-nav-link {{ request()->routeIs('profile.*') ? 'ff-nav-active' : 'ff-nav-inactive' }}"
                >
                    Profiel
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="ff-nav-link ff-nav-inactive w-full text-left">
                        Uitloggen
                    </button>
                </form>
            @endguest
        </div>
    </div>
</nav>
