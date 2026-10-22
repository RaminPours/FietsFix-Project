@extends('layouts.app')
@section('content')
    <x-page-hero image="fietsrep.jpg" eyebrow="Fietsonderhoud" title="Goed onderhouden. Lekker fietsen." description="Een soepel lopende ketting, goed afgestelde remmen en banden op spanning. Kleine details zorgen samen voor een fijne rit.">
        <a href="{{ route('afspraken.create') }}" class="ff-primary">
            Plan een onderhoudsbeurt ↗
        </a>
        <a href="#controle" class="ff-button border border-white/40 text-white hover:bg-white/10">
            Wat controleren we? ↓
        </a>
    </x-page-hero>

    <section id="controle" class="ff-section">
        <div class="grid gap-8 md:grid-cols-2 md:items-center">
            <div>
                <p class="ff-eyebrow">
                    Aandacht voor je fiets
                </p>
                <h2 class="ff-title">
                    De belangrijkste onderdelen in beeld.
                </h2>
                <p class="mt-5 leading-relaxed text-slate-600">
                    Regelmatig onderhoud helpt slijtage op tijd te herkennen. Dit zijn onderdelen die tijdens een onderhoudsbeurt aandacht krijgen.
                </p>
            </div>
            <img src="{{ asset('images/fietsfix-monteur-e-bike.png') }}" alt="FietsFix-monteur werkt aan een nieuwe elektrische fiets" loading="lazy" class="mx-auto w-4/5 rounded-3xl object-cover shadow-md" width="1536" height="1024">
        </div>
        <div class="mt-9 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ([
                ['01', 'Remmen', 'Controle van de werking, slijtage en afstelling van je remmen.'],
                ['02', 'Banden & wielen', 'Aandacht voor bandenspanning, bandenprofiel, spaken en de loop van je wielen.'],
                ['03', 'Ketting & aandrijving', 'Controle van slijtage en smering voor een soepel lopende aandrijving.'],
                ['04', 'Versnellingen', 'Controle van de afstelling, zodat je prettig kunt schakelen.']
            ] as [$number, $heading, $text])
                <article class="ff-card">
                    <span class="text-sm font-bold text-teal-600">
                        {{ $number }}
                    </span>
                    <h3 class="mt-5 text-xl font-bold text-slate-900">
                        {{ $heading }}
                    </h3>
                    <p class="mt-3 leading-relaxed text-slate-600">
                        {{ $text }}
                    </p>
                </article>
            @endforeach
        </div>
    </section>

    <section class="bg-teal-800 text-white">
        <div class="ff-section grid gap-8 md:grid-cols-[1fr_auto] md:items-center">
            <div>
                <p class="text-sm font-bold uppercase tracking-widest text-teal-200">FietsFix abonnement</p>
                <h2 class="mt-3 text-3xl font-bold">Zorgeloos fietsen met FietsFix.</h2>
                <p class="mt-4 max-w-2xl leading-relaxed text-teal-50">
                    Met een zorgeloos fietsabonnement bij FietsFix hoef je je geen zorgen te maken. Is je fiets kapot of werkt iets niet zoals het hoort? Kom langs in de werkplaats en we helpen je weer veilig op weg.
                </p>
            </div>
            <a href="{{ route('contact') }}" class="ff-button bg-white text-teal-900 hover:bg-teal-50">Vraag naar het abonnement</a>
        </div>
    </section>

    <section class="bg-slate-50">
        <div class="ff-section grid gap-10 md:grid-cols-2">
            <div>
                <p class="ff-eyebrow">
                    Tussen de onderhoudsbeurten
                </p>
                <h2 class="ff-title">
                    Een beetje aandacht helpt.
                </h2>
                <p class="mt-5 leading-relaxed text-slate-600">
                    Controleer regelmatig je bandenspanning en houd je ketting schoon en gesmeerd. Hoor je een nieuw geluid of voelt je fiets anders? Geef het door bij je afspraak.
                </p>
            </div>
            <div class="ff-card">
                <h3 class="text-xl font-bold text-slate-900">
                    Twijfel je wat er nodig is?
                </h3>
                <p class="mt-4 leading-relaxed text-slate-600">
                    Beschrijf de klacht, het type fiets en wanneer het probleem optreedt. Zo kunnen we je vraag beter beoordelen. Neem voor de actuele prijs en mogelijkheden contact met ons op.
                </p>
                <a href="{{ route('contact') }}" class="ff-link mt-6">
                    Stel je onderhoudsvraag →
                </a>
            </div>
        </div>
    </section>
    <x-service-cta />
@endsection
