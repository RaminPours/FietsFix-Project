@extends('layouts.app')

@section('content')
    <section class="ff-section max-w-5xl">
        <a href="{{ route('personeel.index') }}" class="ff-link">Terug naar personeelsbeheer</a>
        <div class="mt-8 border-b border-slate-200 pb-7">
            <p class="ff-eyebrow">Alleen voor personeel</p>
            <h1 class="ff-title">Voorraad beheren</h1>
            <p class="mt-3 max-w-2xl text-slate-600">Bekijk de actuele voorraad en pas per product het aantal aan. Vul het aantal stuks in dat je wilt toevoegen of verwijderen.</p>
        </div>

        @if (session('success'))
            <p role="status" class="mt-6 rounded-xl bg-teal-50 p-5 text-teal-800">{{ session('success') }}</p>
        @endif
        @if ($errors->any())
            <ul role="alert" class="mt-6 list-disc rounded-xl bg-red-50 p-6 text-red-700">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <div class="mt-8 flex items-center justify-between gap-4">
            <h2 class="text-xl font-bold text-slate-900">Producten</h2>
            <span class="text-sm text-slate-500">{{ $producten->count() }} producten</span>
        </div>

        <div class="mt-4 space-y-5">
            @forelse ($producten as $product)
                <article class="ff-card overflow-hidden !p-0">
                    <div class="flex flex-wrap items-center justify-between gap-4 px-6 py-5 sm:px-7">
                        <div>
                            <h3 class="text-lg font-bold text-slate-900">{{ $product->naam }}</h3>
                            <p class="mt-1 text-sm text-slate-500">{{ $product->categorie }}</p>
                        </div>
                        <div class="rounded-xl px-4 py-2 text-right {{ $product->voorraad <= 2 ? 'bg-amber-50 text-amber-800' : 'bg-teal-50 text-teal-800' }}">
                            <span class="block text-xs font-semibold">Huidige voorraad</span>
                            <span class="block text-xl font-bold">{{ $product->voorraad }} <span class="text-sm font-medium">stuks</span></span>
                        </div>
                    </div>

                    <div class="space-y-4 border-t border-slate-200 bg-slate-50 px-6 py-5 sm:px-7">
                        <form method="POST" action="{{ route('personeel.voorraad.aanvullen', $product) }}" class="flex flex-wrap items-end gap-3">
                            @csrf
                            @method('PATCH')
                            <div>
                                <label for="aantal-{{ $product->id }}" class="mb-1 block text-sm font-semibold text-slate-700">Aantal toevoegen</label>
                                <input id="aantal-{{ $product->id }}" type="number" name="aantal" value="1" min="1" max="1000" class="ff-input !h-10 !w-28 !py-0" required>
                            </div>
                            <button type="submit" class="ff-primary !h-10 !px-4 !py-0 !text-xs ml-4">Aanvullen</button>
                        </form>

                        @if ($product->voorraad > 0)
                            <form method="POST" action="{{ route('personeel.voorraad.verlagen', $product) }}" class="flex flex-wrap items-end gap-3 border-t border-slate-200 pt-4">
                                @csrf
                                @method('PATCH')
                                <div>
                                    <label for="verwijderen-{{ $product->id }}" class="mb-1 block text-sm font-semibold text-slate-700">Aantal verwijderen</label>
                                    <input id="verwijderen-{{ $product->id }}" type="number" name="aantal" value="1" min="1" max="{{ $product->voorraad }}" class="ff-input !h-10 !w-28 !py-0" required>
                                </div>
                                <button type="submit" class="ff-secondary !h-10 !px-4 !py-0 !text-xs">Verwijderen</button>
                            </form>
                        @endif
                    </div>
                </article>
            @empty
                <p class="ff-card text-slate-600">Er zijn nog geen producten om te beheren.</p>
            @endforelse
        </div>
    </section>
@endsection
