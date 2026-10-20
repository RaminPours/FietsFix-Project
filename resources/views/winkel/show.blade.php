@extends('layouts.app')
@section('content')
    @php
        $foto = $product->foto();
    @endphp
    <section class="ff-section">
        <a href="{{ route($accessoire ? 'winkel.accessoires' : 'winkel.index') }}" class="ff-link">{{ $accessoire ? 'Terug naar alle accessoires' : 'Terug naar alle fietsen' }}</a>
        <div class="mt-8 grid gap-10 lg:grid-cols-2">
            <div>
                <img src="{{ asset($product->afbeelding) }}" alt="{{ $foto['model'] ?? $product->naam }}" class="aspect-[720/440] w-full rounded-3xl bg-white object-contain" width="720" height="440">
                @if ($foto)
                    <p class="mt-3 text-sm text-slate-500">Op de foto: {{ $foto['model'] }}. <a href="{{ $foto['bron'] }}" class="underline">Fotobron: {{ $foto['eigenaar'] }}</a>.</p>
                @endif
            </div>
            <div>
                <p class="ff-eyebrow">{{ $product->merk }} · {{ $product->categorie }}</p>
                <h1 class="ff-title">{{ $product->naam }}</h1>
                <h2 class="mt-6 text-xl font-bold text-slate-900">{{ $accessoire ? 'Beschrijving' : 'Over deze fiets' }}</h2>
                <p class="mt-5 leading-relaxed text-slate-600">{{ $product->beschrijving }}</p>
                <p class="mt-6 text-3xl font-bold">&euro; {{ number_format($product->prijs_cent / 100, 2, ',', '.') }}</p>
                <p class="mt-3 text-slate-600">Bestel voor afhalen bij een vestiging. Betalen doe je in de winkel. Afhalen kan na bevestiging.</p>
                @if ($product->voorraad > 0)
                    <p class="mt-3 text-sm text-slate-600">Nog {{ $product->voorraad }} op voorraad.</p>
                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="{{ route('bestellingen.create', $product) }}" class="ff-primary">Bestellen</a>
                        <form method="POST" action="{{ route('winkelmand.toevoegen', $product) }}">
                            @csrf
                            <button type="submit" class="ff-secondary">Toevoegen aan winkelmandje</button>
                        </form>
                    </div>
                @else
                    <p class="mt-6 font-semibold text-amber-600">Niet op voorraad.</p>
                @endif
                @guest
                    <p class="mt-3 text-sm text-slate-500">Log in om je bestelling te plaatsen.</p>
                @endguest
            </div>
        </div>
        <div class="ff-card mt-10">
            <h2 class="text-2xl font-bold">Specificaties</h2>
            {{-- Elke specificatie bestaat uit een naam (bijvoorbeeld Accu) en een waarde. --}}
            <dl class="mt-5 divide-y divide-slate-200">
                @foreach ($product->specificaties as $naam => $waarde)
                    <div class="grid gap-2 py-4 sm:grid-cols-2">
                        <dt class="font-semibold">{{ $naam }}</dt>
                        <dd class="text-slate-600">{{ $waarde }}</dd>
                    </div>
                @endforeach
            </dl>
        </div>
        <a href="{{ route('contact') }}" class="ff-link mt-8">Hulp nodig bij het kiezen? Neem contact op</a>
    </section>
@endsection
