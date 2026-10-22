@extends('layouts.app')
@section('content')
    <section class="ff-section">
        <p class="ff-eyebrow">FietsFix Shop</p>
        <h1 class="ff-title">Je winkelmandje</h1>
        <p class="mt-4 text-slate-600">Controleer je artikelen en aantallen. Je betaalt bij het afhalen in de winkel.</p>
        @if (session('success'))
            <p role="status" class="mt-6 rounded-xl bg-teal-50 p-4 text-teal-800">{{ session('success') }}</p>
        @endif
        @if ($errors->any())
            <ul role="alert" class="mt-6 list-disc rounded-xl bg-red-50 p-6 text-red-700">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
        <div class="mt-8 space-y-5">
            @forelse ($mandje as $productId => $aantal)
                @php
                    // Zoek het product bij dit nummer. Het kan inmiddels verwijderd zijn.
                    $product = $producten->firstWhere('id', $productId);
                    $foto = $product?->foto();
                @endphp
                <article class="ff-card grid gap-6 md:grid-cols-3 md:items-center">
                    @if ($product)
                        <img src="{{ asset($product->afbeelding) }}" alt="{{ $foto['model'] ?? $product->naam }}" class="h-48 w-full rounded-2xl bg-white object-contain" width="720" height="440">
                        <div>
                            <p class="ff-eyebrow">{{ $product->merk }} {{ $product->categorie }}</p>
                            <h2 class="mt-2 text-xl font-bold">{{ $product->naam }}</h2>
                            <p class="mt-3">&euro; {{ number_format($product->prijs_cent / 100, 2, ',', '.') }} per stuk</p>
                            <p class="mt-2 font-bold">Subtotaal: &euro; {{ number_format($product->prijs_cent * $aantal / 100, 2, ',', '.') }}</p>
                            @if (!$product->actief)
                                <p class="mt-3 text-red-700">Niet meer beschikbaar. Verwijder dit artikel om verder te gaan.</p>
                            @endif
                        </div>
                    @else
                        <p class="text-red-700 md:col-span-2">Dit artikel is verwijderd uit het assortiment. Verwijder het uit je mandje om verder te gaan.</p>
                    @endif
                    <div class="space-y-4">
                        @if ($product && $product->actief)
                            <form method="POST" action="{{ route('winkelmand.wijzigen', $productId) }}">
                                @csrf
                                @method('PATCH')
                                <label for="aantal-{{ $productId }}" class="mb-2 block font-semibold">Aantal</label>
                                <div class="flex gap-2">
                                    <select id="aantal-{{ $productId }}" name="aantal" class="ff-input" onchange="this.form.submit()">
                                        @for ($keuze = 1; $keuze <= 5; $keuze++)
                                            <option value="{{ $keuze }}" @selected($keuze === $aantal)>{{ $keuze }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </form>
                        @endif
                        <form method="POST" action="{{ route('winkelmand.verwijderen', $productId) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="ff-link">Verwijderen</button>
                        </form>
                    </div>
                </article>
            @empty
                <div class="ff-card"><p>Je winkelmandje is nog leeg. Kies een artikel in de shop.</p></div>
            @endforelse
        </div>
        <div class="mt-8 flex flex-wrap items-center justify-between gap-6">
            <a href="{{ route('winkel.index') }}" class="ff-secondary">Verder winkelen</a>
            @if (count($mandje))
                <div>
                    <p class="mb-4 text-2xl font-bold">Totaal: &euro; {{ number_format($totaal / 100, 2, ',', '.') }}</p>
                    @if ($kanBestellen)
                        <a href="{{ route('afrekenen.create') }}" class="ff-primary">Doorgaan naar bestellen</a>
                        @guest
                            <p class="mt-3 text-sm text-slate-600">Bij de volgende stap log je in. Je mandje blijft bewaard.</p>
                        @endguest
                    @else
                        <p class="text-red-700">Verwijder de niet beschikbare artikelen om verder te gaan.</p>
                    @endif
                </div>
            @endif
        </div>
    </section>
@endsection
