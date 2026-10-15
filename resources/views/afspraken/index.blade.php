@extends('layouts.app')
@section('content')
    <div class="bg-teal-50 px-6 py-3 text-center text-sm font-semibold text-teal-900">
        Nieuwe klant? Ontvang 20% korting op je eerste bezoek.
    </div>
    <x-page-hero
        image="fietsfix.jpg"
        eyebrow="FietsFix · Reparatie & onderhoud"
        title="Jouw fiets, onze zorg."
        description="Van je dagelijkse rit tot een lange fietstocht. Wij helpen je fiets in goede conditie te houden."
    >
        <a href="{{ route('afspraken.create') }}" class="ff-primary">
            Plan een afspraak ↗
        </a>
        <a href="{{ route('fietsonderhoud') }}" class="ff-button border border-white/40 text-white hover:bg-white/10">
            Ontdek ons onderhoud →
        </a>
    </x-page-hero>

    <section class="ff-section">
        <p class="ff-eyebrow">
            Waar kunnen we je mee helpen?
        </p>
        <h2 class="ff-title">
            Meer plezier van iedere rit.
        </h2>
        <div class="mt-9 grid gap-6 md:grid-cols-3">
            @foreach ([
                ['merken', '01', 'Voor iedere fiets', 'E-bike, stadsfiets of bakfiets: ontdek welke fietsen bij ons welkom zijn.', 'Bekijk merken'],
                ['fietsonderhoud', '02', 'Aandacht voor de details', 'Van remmen en banden tot ketting en versnellingen. Bekijk wat onderhoud inhoudt.', 'Ontdek onderhoud'],
                ['contact', '03', 'Een vraag over je fiets?', 'Vind antwoorden op veelgestelde vragen of neem contact met ons op.', 'We helpen je verder']
            ] as [$destination, $number, $heading, $text, $label])
                <article class="ff-card flex flex-col">
                    <span class="mb-6 text-sm font-bold text-teal-600">
                        {{ $number }} / FIETSFIX
                    </span>
                    <h3 class="text-xl font-bold text-slate-900">
                        {{ $heading }}
                    </h3>
                    <p class="mb-7 mt-3 leading-relaxed text-slate-600">
                        {{ $text }}
                    </p>
                    <a
                        href="{{ route($destination) }}"
                        class="ff-link mt-auto"
                    >
                        {{ $label }} →
                    </a>
                </article>
            @endforeach
        </div>
    </section>

    <section class="bg-slate-50">
        <div class="ff-section grid gap-10 md:grid-cols-2 md:items-center">
            <div>
                <p class="ff-eyebrow">
                    Zo werkt het
                </p>
                <h2 class="ff-title">
                    Je afspraak, eenvoudig geregeld.
                </h2>
                <p class="mt-5 leading-relaxed text-slate-600">
                    Maak een account aan, plan je afspraak en bekijk je afspraken op één plek.
                </p>
            </div>
            <ol class="space-y-4">
                @foreach ([
                    'Log in of maak een account aan.',
                    'Vul je gegevens in en kies een moment.',
                    'Bekijk je afspraak via je account.'
                ] as $step)
                    <li
                        class="flex items-center gap-4 rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-teal-50 font-bold text-teal-800">
                            {{ $loop->iteration }}
                        </span>
                        <span class="text-slate-700">
                            {{ $step }}
                        </span>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    <section
        id="afspraak"
        class="ff-section">
        <div
            class="text-center">
        <p class="ff-eyebrow">
                Online afspraak
            </p>
            <h2 class="ff-title">
                Wanneer zien we jouw fiets?
            </h2>
            <p class="mt-4 text-slate-600">
                Kies een moment dat jou uitkomt.
            </p>
        </div>
        @auth
            @include('afspraken.form')
        @else
            <div
                class="mt-8 flex flex-wrap justify-center gap-3">
                <a
                    href="{{ route('afspraken.create') }}" class="ff-primary">
                Inloggen en afspraak maken ↗
                </a>
                <a
                    href="{{ route('register') }}" class="ff-secondary">
                Account aanmaken
                </a>
                </div>
                @endauth
                </section>
                @endsection
