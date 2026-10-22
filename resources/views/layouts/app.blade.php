<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>
            {{ config('app.name', 'FietsFix') }}
        </title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased min-h-screen flex flex-col">
        @include('layouts.navigation')
        
       
        <main class="flex-1">
            @isset($header)
                <header class="max-w-6xl mx-auto px-6 pt-8">
                    {{ $header }}
                </header>
            @endisset
            @yield('content')
            {{ $slot ?? '' }}
        </main>
        
        
        <footer class="bg-slate-900 text-slate-200">
            <div class="mx-auto grid max-w-8xl grid-cols-1 gap-8 px-4 py-8 sm:px-6 sm:py-10 md:grid-cols-2 md:gap-10 lg:grid-cols-3 lg:px-8 lg:py-12">
                <div>
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-4 rounded-lg focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-teal-400" aria-label="FietsFix home">
                        <span class="rounded-xl bg-white p-2">
                            <x-application-logo class="h-14 w-14" />
                        </span>
                        <span class="text-xl font-bold text-white">FietsFix</span>
                    </a>
                    <p class="mt-4 text-sm leading-relaxed text-slate-400">Jouw fiets, onze zorg.</p>
                </div>

                <div>
                    <h2 class="font-semibold text-white">Adres</h2>
                    <p class="mt-3 text-sm leading-relaxed text-slate-400 sm:mt-4">FietsFix, Hoofdstraat 123, 1234 AB Fietsstad</p>
                </div>

                <div>
                    <h2 class="font-semibold text-white">Klantenservice</h2>
                    <a href="{{ route('contact') }}" class="mt-3 inline-block max-w-full text-sm leading-relaxed text-slate-400 underline decoration-slate-600 underline-offset-4 hover:text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-teal-400 sm:mt-4">
                        Beschikbaar van maandag tot en met vrijdag van 9:00 tot 17:00 uur
                    </a>
                </div>
            </div>
            <div class="border-t border-white/10 text-center">
                <p class="mx-auto max-w-7xl px-4 py-5 text-center text-xs leading-relaxed text-slate-400 sm:px-6 sm:text-sm lg:px-8">
                    &copy; {{ date('Y') }} FietsFix. Alle rechten voorbehouden.
                </p>
            </div>
        </footer>
    </body>
</html>
