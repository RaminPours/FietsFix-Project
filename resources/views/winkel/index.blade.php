@extends('layouts.app')
@section('content')
    <section class="bg-teal-50">
        <div class="ff-section">
            @auth
                <div role="status" class="relative z-10 -mt-1 mb-8 rounded-2xl bg-white px-4 py-3 text-teal-900 shadow-sm">
                    <p class="font-bold">Welkom terug, {{ auth()->user()->name }}!</p>
                    <p class="mt-1">Als geregistreerde FietsFix-klant ontvang je extra klantkorting in de webshop.</p>
                </div>
            @endauth
            @if ($accessoires)
                <h1 class="ff-title">Alles voor jouw fiets.</h1>
                <p class="mt-5 max-w-2xl text-lg text-slate-600">Van verlichting en sloten tot banden en pedalen. Kies een categorie, bekijk de onderdelen en voeg ze toe aan je winkelmandje.</p>
                <a href="{{ route('proefrit.create') }}" class="ff-primary mt-6">Plan een proefrit</a>
            @else
                <div class="grid gap-8 lg:grid-cols-2 lg:items-center">
                    <div>
                        <h1 class="ff-title">Vind de fiets die bij je past.</h1>
                        <p class="mt-5 max-w-2xl text-lg text-slate-600">Voor de stad, een langere rit of het hele gezin. Bekijk onze fietsen, ontdek de specificaties en bestel voor afhalen bij jouw vestiging.</p>
                        <a href="{{ route('proefrit.create') }}" class="ff-primary mt-6">Plan een proefrit</a>
                    </div>
                    <img src="{{ asset('images/winkel/uitgelichte-giant-e-bike.png') }}" alt="Populaire Giant e-bike, alleen bij FietsFix afgeprijsd van 3.500 euro naar 2.999 euro" class="w-full rounded-3xl object-cover shadow-lg" width="1536" height="1024">
                </div>
            @endif
        </div>
    </section>
    <section class="ff-section">
        {{-- De gekozen categorie wordt via de URL naar Laravel gestuurd. --}}
        <form action="{{ route($accessoires ? 'winkel.accessoires' : 'winkel.index') }}" method="GET" class="mb-8 flex flex-wrap items-end gap-4">
            <div>
                <label for="categorie" class="mb-2 block font-semibold">{{ $accessoires ? 'Welk onderdeel zoek je?' : 'Wat voor fiets zoek je?' }}</label>
                <select id="categorie" name="categorie" class="ff-input">
                    <option value="">{{ $accessoires ? 'Alle accessoires' : 'Alle fietsen' }}</option>
                    @foreach ($categorieen as $categorie)
                        <option value="{{ $categorie }}" @selected(request('categorie') === $categorie)>{{ $categorie }}</option>
                    @endforeach
                </select>
            </div>
            @if (!$accessoires)
            <div>
                <label for="merk" class="mb-2 block font-semibold">Merk</label>
                <select id="merk" name="merk" class="ff-input w-44">
                    <option value="">Alle merken</option>
                    @foreach ($merken as $merk)
                        <option value="{{ $merk }}" @selected(request('merk') === $merk)>{{ $merk }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            <button class="ff-primary" type="submit">{{ $accessoires ? 'Toon accessoires' : 'Toon fietsen' }}</button>
            <a class="ff-link" href="{{ route($accessoires ? 'winkel.accessoires' : 'winkel.index') }}">Alles bekijken</a>
        </form>
        @if ($errors->any())
            <p role="alert" class="mb-6 text-red-700">Kies een geldige categorie en een geldig merk.</p>
        @endif
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse ($producten as $product)
                @php
                    $foto = $product->foto();
                @endphp
                <article class="ff-card flex flex-col">
                    <a href="{{ route('winkel.show', $product) }}">
                        <img src="{{ asset($product->afbeelding) }}" alt="{{ $foto['model'] ?? $product->naam }}" class="mb-3 h-56 w-full rounded-2xl bg-white object-contain" width="720" height="440" loading="lazy">
                    </a>
                    @if ($foto)
                        <p class="mb-4 text-xs text-slate-500">Foto: {{ $foto['model'] }}. <a href="{{ $foto['bron'] }}" class="underline">Bron: {{ $foto['eigenaar'] }}</a></p>
                    @endif
                    <p class="ff-eyebrow">{{ $product->merk }} · {{ $product->categorie }}</p>
                    <h2 class="mt-2 text-2xl font-bold text-slate-900">{{ $product->naam }}</h2>
                    <p class="mt-4 flex-1 text-slate-600">{{ \Illuminate\Support\Str::limit($product->beschrijving, 110) }}</p>
                    <p class="my-5 text-2xl font-bold">&euro; {{ number_format($product->prijs_cent / 100, 2, ',', '.') }}</p>
                    @unless ($accessoires)
                        <p class="-mt-4 mb-5 text-xs text-slate-500">5% korting voor klanten met een abonnement bij FietsFix.</p>
                    @endunless
                    <a class="ff-primary" href="{{ route('winkel.show', $product) }}">{{ $accessoires ? 'Bekijk dit artikel' : 'Bekijk deze fiets' }}</a>
                    <form method="POST" action="{{ route('winkelmand.toevoegen', $product) }}" class="mt-3">
                        @csrf
                        <button type="submit" class="ff-secondary w-full">Bestellen</button>
                    </form>
                </article>
            @empty
                <p>{{ $accessoires ? 'Er zijn op dit moment geen accessoires in deze categorie.' : 'Er zijn op dit moment geen fietsen in deze categorie.' }}</p>
            @endforelse
        </div>
        <div class="mt-8">{{ $producten->links() }}</div>
    </section>
@endsection
