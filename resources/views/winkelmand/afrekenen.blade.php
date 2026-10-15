@extends('layouts.app')
@section('content')
    <section class="ff-section max-w-3xl">
        <a href="{{ route('winkelmand.index') }}" class="ff-link">Terug naar je winkelmandje</a>
        <h1 class="ff-title">Je bestelling afronden</h1>
        <p class="mt-4 text-slate-600">Kies een afhaalvestiging. Betalen doe je in de winkel. Wacht op bevestiging voordat je langskomt.</p>
        <div class="ff-card mt-8">
            <h2 class="text-xl font-bold">Jouw artikelen</h2>
            <ul class="mt-4 divide-y divide-slate-200">
                @foreach ($producten as $product)
                    <li class="flex flex-wrap justify-between gap-3 py-3">
                        <span>{{ $mandje[$product->id] }} × {{ $product->naam }}</span>
                        <span>&euro; {{ number_format($product->prijs_cent * $mandje[$product->id] / 100, 2, ',', '.') }}</span>
                    </li>
                @endforeach
            </ul>
            <p class="mt-4 text-2xl font-bold">Totaal: &euro; {{ number_format($totaal / 100, 2, ',', '.') }}</p>
        </div>
        @if ($errors->any())
            <ul role="alert" class="mt-6 list-disc rounded-xl bg-red-50 p-6 text-red-700">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
        <form method="POST" action="{{ route('afrekenen.store') }}" class="ff-card mt-8 space-y-5">
            @csrf
            {{-- Alleen het formuliernummer gaat mee. Prijzen worden op de server gecontroleerd. --}}
            <input type="hidden" name="formuliernummer" value="{{ $formuliernummer }}">
            <div>
                <label for="naam" class="mb-1 block font-semibold">Naam</label>
                <input id="naam" name="naam" class="ff-input" value="{{ old('naam', auth()->user()->name) }}" maxlength="255" autocomplete="name" required>
            </div>
            <div>
                <label for="email" class="mb-1 block font-semibold">E-mailadres</label>
                <input id="email" type="email" name="email" class="ff-input" value="{{ old('email', auth()->user()->email) }}" maxlength="255" autocomplete="email" required>
            </div>
            <div>
                <label for="telefoonnummer" class="mb-1 block font-semibold">Telefoonnummer</label>
                <input id="telefoonnummer" type="tel" name="telefoonnummer" class="ff-input" value="{{ old('telefoonnummer') }}" maxlength="20" autocomplete="tel" required>
            </div>
            <div>
                <label for="vestiging" class="mb-1 block font-semibold">Afhalen bij</label>
                <select id="vestiging" name="vestiging" class="ff-input" required>
                    <option value="">Kies een vestiging</option>
                    @foreach (config('vestigingen.locaties') as $vestiging)
                        <option value="{{ $vestiging }}" @selected(old('vestiging') === $vestiging)>{{ $vestiging }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="ff-primary">Bestelling plaatsen — betalen in de winkel</button>
        </form>
    </section>
@endsection
