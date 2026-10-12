@php
    $linkClass = 'rounded-xl px-4 py-3 text-sm font-semibold transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-teal-600 focus-visible:ring-offset-2';
    $inactiveClass = 'text-slate-600 hover:bg-slate-50 hover:text-teal-700';
    $primaryClass = 'inline-flex items-center justify-center rounded-xl bg-teal-700 px-5 py-3 text-sm font-bold text-white shadow-sm transition-colors hover:bg-teal-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-teal-600 focus-visible:ring-offset-2';
@endphp
<nav x-data="{ open: false }" @keydown.escape.window="open = false" class="border-b border-slate-100 bg-white text-slate-700 shadow-sm" aria-label="Hoofdnavigatie">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex flex-wrap items-center justify-between gap-x-4 py-2">
            <a href="{{ route('home') }}" class="shrink-0 rounded-xl focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-teal-600" aria-label="FietsFix home">
                <x-application-logo class="h-20 w-20 sm:h-24 sm:w-24" />
            </a>
            <button type="button" @click="open = !open" :aria-expanded="open.toString()" aria-controls="navigation-links" class="inline-flex min-h-11 items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition-colors hover:bg-slate-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-teal-600 lg:hidden">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" aria-hidden="true">
                    <path :d="open ? 'M6 6l12 12M6 18L18 6' : 'M4 6h16M4 12h16M4 18h16'" />
                </svg>
                <span x-text="open ? 'Sluiten' : 'Menu'">Menu</span>
            </button>
            <div id="navigation-links" :class="{ 'hidden': !open }" class="hidden w-full border-t border-slate-100 pb-4 pt-3 lg:flex lg:w-auto lg:items-center lg:gap-6 lg:border-0 lg:p-0">
                <div class="flex flex-col gap-1 lg:flex-row lg:items-center">
                    @foreach (['home' => 'Home', 'fietssoorten' => 'Fietssoorten', 'fietsonderhoud' => 'Fietsonderhoud', 'contact' => 'Contact'] as $routeName => $label)
                        <a href="{{ route($routeName) }}" @if(request()->routeIs($routeName)) aria-current="page" @endif class="{{ $linkClass }} {{ request()->routeIs($routeName) ? 'bg-teal-50 text-teal-800' : $inactiveClass }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
                <div class="mt-3 flex flex-col gap-2 border-t border-slate-100 pt-3 lg:mt-0 lg:flex-row lg:items-center lg:border-l lg:border-t-0 lg:pl-6 lg:pt-0">
                    @guest
                        <a href="{{ route('login') }}" @if(request()->routeIs('login')) aria-current="page" @endif class="{{ $linkClass }} {{ request()->routeIs('login') ? 'bg-teal-50 text-teal-800' : $inactiveClass }}">
                            Inloggen
                        </a>
                        <a href="{{ route('register') }}" @if(request()->routeIs('register')) aria-current="page" @endif class="{{ $primaryClass }}">
                            Registreren
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" @if(request()->routeIs('dashboard', 'afspraken.*')) aria-current="page" @endif class="{{ $primaryClass }}">
                            Afspraak inzien
                        </a>
                        <a href="{{ route('profile.edit') }}" @if(request()->routeIs('profile.*')) aria-current="page" @endif class="{{ $linkClass }} {{ request()->routeIs('profile.*') ? 'bg-teal-50 text-teal-800' : $inactiveClass }}">
                            Profiel
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="{{ $linkClass }} {{ $inactiveClass }} w-full text-left">
                                Uitloggen
                            </button>
                        </form>
                    @endguest
                </div>
            </div>
        </div>
    </div>
</nav>
