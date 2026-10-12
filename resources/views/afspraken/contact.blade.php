@extends('layouts.app')

@section('content')
    <x-page-hero
        image="image.png"
        eyebrow="Contact & hulp"
        title="Hoe kunnen we je helpen?"
        description="Een vraag over onderhoud, jouw type fiets of een afspraak? Bekijk de antwoorden hieronder of stuur ons een e-mail."
    >
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
                    ['Waar vind ik mijn afspraak?', 'Na het inloggen vind je je afspraken via ‘Afspraak inzien’ in de navigatie.'],
                ] as [$question, $answer])
                    <details class="group rounded-2xl border border-slate-200 bg-white">
                        <summary
                            class="cursor-pointer rounded-2xl p-5 font-semibold text-slate-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-teal-600"
                        >
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
