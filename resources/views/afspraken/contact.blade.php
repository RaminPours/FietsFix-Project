@extends('layouts.app')
@section('content')
    <x-page-hero image="service.jpg" eyebrow="Contact & hulp" title="Hoe kunnen we je helpen?" description="Een vraag over onderhoud, jouw type fiets of een afspraak? Bekijk de antwoorden hieronder of stuur ons een e-mail.">
        <a href="mailto:klanten@fietsfix.nl" class="ff-primary">
            Stuur een e-mail ↗
        </a>
        <a href="#vragen" class="ff-button border border-white/40 text-white hover:bg-white/10">
            Veelgestelde vragen ↓
        </a>
    </x-page-hero>

    <section class="ff-section grid gap-8 md:grid-cols-2">
        <article class="ff-card">
            <p class="ff-eyebrow">
                Neem contact op
            </p>
            <h2 class="ff-title">
                We denken met je mee.
            </h2>
            <p class="mt-5 leading-relaxed text-slate-600">
                Vermeld bij je vraag het merk en type van je fiets en beschrijf kort waar je hulp bij nodig hebt.
            </p>
            <div class="mt-6 flex items-center gap-4 rounded-2xl bg-teal-50 p-4 text-slate-700">
                <button type="button" aria-label="Start live chat" class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-teal-600 text-white transition hover:bg-teal-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-teal-600 focus-visible:ring-offset-2">
                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 3.866-4.03 7-9 7a10.6 10.6 0 0 1-4.25-.88L3 19l1.37-3.2A6.52 6.52 0 0 1 3 12c0-3.866 4.03-7 9-7s9 3.134 9 7Z" />
                    </svg>
                </button>
                <div>
                    <p class="font-semibold text-teal-900">Live chat tijdens kantoortijden</p>
                    <p class="mt-1 text-sm leading-relaxed">Naast contact per e-mail is onze klantenservice tijdens kantoortijden ook via live chat beschikbaar.</p>
                </div>
            </div>
            <a href="mailto:klanten@fietsfix.nl" class="ff-link mt-6 break-all">
                klanten@fietsfix.nl ↗
            </a>
        </article>
        <article class="rounded-3xl bg-teal-50 p-7 sm:p-8">
            <p class="ff-eyebrow">
                Je afspraak beheren
            </p>
            <h2 class="mt-3 text-2xl font-bold text-slate-900">
                Alles op één plek.
            </h2>
            <p class="mt-5 leading-relaxed text-slate-600">
                Log in om je afspraken te bekijken. Nog geen afspraak? Je kunt die eenvoudig online inplannen.
            </p>
            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ route('dashboard') }}" class="ff-primary">
                    Mijn afspraken →
                </a>
                <a href="{{ route('afspraken.create') }}" class="ff-secondary">
                    Afspraak maken
                </a>
            </div>
        </article>
    </section>

    <section id="vragen" class="bg-slate-50">
        <div class="ff-section">
            <p class="ff-eyebrow">
                Goed om te weten
            </p>
            <h2 class="ff-title">
                Veelgestelde vragen.
            </h2>
            <div class="mt-8 max-w-3xl space-y-3">
                @foreach ([
                    ['Hoe maak ik een afspraak?', 'Log in of maak een account aan. Via ‘Plan een afspraak’ kun je je gegevens invullen en een moment kiezen.'],
                    ['Kan ik met ieder fietsmerk terecht?', 'Alle merken zijn welkom. Heb je een bijzondere fiets of een specifieke reparatievraag? Mail ons dan vooraf met het merk en type.'],
                    ['Wat kost een onderhoudsbeurt?', 'Neem contact met ons op voor de actuele prijs en mogelijkheden voor jouw fiets. Vermeld het type fiets en wat je wilt laten controleren.'],
                    ['Wat kost een FietsFix abonnement?', 'Een zorgeloos FietsFix abonnement kost € 20 per maand (maandelijks opzegbaar). Je krijgt korting op onderhoudsbeurten en gratis pechhulp.'],
                    ['Waar vind ik mijn afspraak?', 'Na het inloggen vind je je afspraken via ‘Afspraak inzien’ in de navigatie.']
                ] as [$question, $answer])
                    <details class="group rounded-2xl border border-slate-200 bg-white">
                        <summary class="cursor-pointer rounded-2xl p-5 font-semibold text-slate-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-teal-600">
                            {{ $question }}
                        </summary>
                        <p class="px-5 pb-5 leading-relaxed text-slate-600">
                            {{ $answer }}
                        </p>
                    </details>
                @endforeach
            </div>
        </div>
    </section>
    <x-service-cta />
@endsection
