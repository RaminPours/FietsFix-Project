@extends('layouts.app')

@section('content')

<section class="relative">

        <img
            src="{{ asset('images/fietsrep.jpg') }}"
            alt="FietsFix"
            class="w-full h-[500px] object-cover"
        >

        <!-- Overlay -->
        <div class="absolute inset-0 bg-black/40"></div>

        <!-- Hero tekst -->
        <div class="absolute inset-0 flex items-center justify-center text-center px-6">

            <div class="text-white">

                <p class="text-4xl md:text-6xl font-bold">
                    Fietsonderhoud
                </p>

            </div>

        </div>

    </section>

     <!-- ANWB PAS -->
    <div class="bg-red-500 text-white text-center py-3">
        <p class="font-bold text-lg">
            🎉 Voordeel met je ANWB ledenpas!
        </p>
    </div>

<div class="mt-16 grid grid-cols-2 gap-0 items-start">

    <!-- Blauwe tekst linksboven -->
    <section class="bg-blue-900 text-white min-h-[400px] flex items-center justify-start px-6 py-10">
        <div class="text-left">
            <h2 class="font-bold text-3xl mb-4">
                Service en onderhoud
            </h2>

            <p class="text-base leading-relaxed">
                Goed onderhoud verlengt de levensduur van je e-bike en voorkomt verrassingen.
                Daarom nemen wij service en onderhoud serieus. Onze moderne werkplaats is
                volledig ingericht voor e-bikes en LEV-voertuigen. Onderhoud en reparaties
                worden uitgevoerd door ervaren monteurs met de juiste kennis en apparatuur.
                We werken volgens fabrieksrichtlijnen en gebruiken waar nodig originele
                onderdelen. Zo blijft je e-bike betrouwbaar, veilig en in topconditie.
                Je kunt eenvoudig online een onderhoudsafspraak inplannen. Is er meer aan
                de hand? Dan nemen we vooraf contact met je op. Geen onverwachte kosten,
                wel duidelijke afspraken.
            </p>
        </div>
    </section>

    
    <img
        src="{{ asset('images/repair.jpg') }}"
        alt="Fietsreparatie"
        class="w-full h-[400px] object-cover"
    >

    
    <img
        src="{{ asset('images/repair2.jpg') }}"
        alt="Onderhoud aan een fiets"
        class="w-full h-[400px] object-cover"
    >

    <section class="bg-blue-900 text-white min-h-[400px] flex items-center justify-start px-6 py-10">
        <div class="text-left">
            <h2 class="font-bold text-3xl mb-4">
                Monteurs die hun vak verstaan en vooruitkijken
            </h2>

            <p class="text-base leading-relaxed">
                Onze monteurs werken elke dag met e-bikes, bakfietsen en andere elektrische voertuigen. Ze weten precies hoe ze een storing snel kunnen vinden en netjes oplossen. We gebruiken onderdelen die lang meegaan en geven eerlijk advies over wat slim is voor later. Dat kan een kleine afstelling zijn, een accucheck of een tip om problemen te voorkomen. Zo blijft uw fiets betrouwbaar, veilig en klaar voor heel wat kilometers, thuis en onderweg.
            </p>
        </div>
    </section>

    <section class="bg-blue-900 text-white min-h-[400px] flex items-center justify-start px-6 py-10">
        <div class="text-left">
            <h2 class="font-bold text-3xl mb-4">
                Prijzen onderhoud
            </h2>

            <p class="text-base leading-relaxed">
                
            </p>
        </div>
    </section>
    
</div>

@endsection