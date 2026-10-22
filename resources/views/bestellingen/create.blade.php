@extends('layouts.app')
@section('content')
    <section class="ff-section max-w-3xl">
        <a href="{{ route('winkel.show', $product) }}" class="ff-link">Terug naar de fiets</a>
        <h1 class="ff-title">Bestel {{ $product->naam }}</h1>
        <p class="mt-4 text-lg">&euro; {{ number_format($product->prijs_cent / 100, 2, ',', '.') }} per fiets.</p>
        <p class="mt-3 text-slate-600">Kies het aantal en de vestiging waar je wilt afhalen. Je betaalt in de winkel. Wacht op bevestiging voordat je langskomt.</p>
        <p class="mt-3 text-sm text-slate-500">Dit is een voorbeeldproduct in het demonstratieassortiment.</p>
        @if ($errors->any())
            <ul role="alert" class="mt-6 list-disc rounded-xl bg-red-50 p-6 text-red-700">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
        <form action="{{ route('bestellingen.store', $product) }}" method="POST" class="ff-card mt-8 space-y-5">
            @csrf
            {{-- Dit nummer voorkomt dat dezelfde bestelling twee keer wordt opgeslagen. --}}
            <input type="hidden" name="formuliernummer" value="{{ $formuliernummer }}">
            <div>
                <label for="aantal" class="mb-1 block font-semibold">Aantal fietsen</label>
                <select id="aantal" name="aantal" class="ff-input" required>
                    @for ($aantal = 1; $aantal <= min(5, $product->voorraad); $aantal++)
                        <option value="{{ $aantal }}" @selected(old('aantal', 1) == $aantal)>{{ $aantal }} — totaal &euro; {{ number_format($product->prijs_cent * $aantal / 100, 2, ',', '.') }}</option>
                    @endfor
                </select>
            </div>
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
            <button class="ff-primary" type="submit">Bestelling plaatsen & betalen in de winkel</button>
        </form>
    </section>
@endsection
