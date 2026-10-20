@extends('layouts.app')

@section('content')
    <section class="ff-section">
        <p class="ff-eyebrow">Alleen voor personeel</p>
        <h1 class="ff-title">Personeelsbeheer</h1>
        <p class="mt-4 text-slate-600">Overzicht van bestellingen en voorraad die aandacht nodig hebben.</p>

        <div class="mt-8 grid gap-6 md:grid-cols-2">
            <article class="ff-card">
                <p class="text-sm font-semibold text-slate-600">Nieuwe bestellingen</p>
                <p class="mt-2 text-4xl font-bold text-teal-700">{{ $openstaandeBestellingen }}</p>
                <p class="mt-2 text-slate-600">Bestellingen met status ‘Ontvangen’.</p>
            </article>
            <article class="ff-card">
                <p class="text-sm font-semibold text-slate-600">Lage voorraad</p>
                <p class="mt-2 text-4xl font-bold text-amber-600">{{ $lageVoorraad->count() }}</p>
                <p class="mt-2 text-slate-600">Producten met nog maximaal 2 stuks op voorraad.</p>
            </article>
        </div>

        <section class="ff-card mt-8">
            <h2 class="text-2xl font-bold">Producten met lage voorraad</h2>
            @forelse ($lageVoorraad as $product)
                <div class="mt-4 flex items-center justify-between border-t border-slate-200 pt-4">
                    <span>{{ $product->naam }}</span>
                    <span class="font-semibold text-amber-600">{{ $product->voorraad }} op voorraad</span>
                </div>
            @empty
                <p class="mt-4 text-slate-600">Alle producten hebben voldoende voorraad.</p>
            @endforelse
        </section>

        <a href="{{ route('personeel.voorraad') }}" class="ff-primary mt-8">Voorraad beheren</a>
    </section>
@endsection
