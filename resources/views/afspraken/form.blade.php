@php
    // Dit formulier staat ook op de homepagina. Daar zijn deze gegevens nog niet opgehaald.
    $bezetteTijden = $bezetteTijden ?? [];
    $beschikbaarheidGeladen = $beschikbaarheidGeladen ?? false;
    $volgeboekt = $volgeboekt ?? false;
@endphp
<div class="max-w-2xl mx-auto px-6 py-10">
    <div class="bg-white rounded-2xl shadow-md p-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">
            Afspraak inplannen 🚲
        </h1>
        <p class="text-gray-500 mb-8">
            Vul hieronder je gegevens in om een afspraak bij FietsFix te maken.
        </p>
        {{-- Toon de foutmeldingen die Laravel bij het controleren van het formulier heeft gevonden. --}}
        @if ($errors->any())
            <div role="alert" class="mb-6 rounded-lg bg-red-50 p-4 text-red-700">
                <p class="font-semibold">
                    Controleer je gegevens:
                </p>
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>
                            {{ $error }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('afspraken.store') }}" method="POST" class="space-y-5">
            {{-- Laravel gebruikt dit token om het versturen van het formulier te beveiligen. --}}
            @csrf
            {{-- old() zet eerder ingevulde gegevens terug na het herladen van het formulier. --}}
            <div>
                <label for="vestiging" class="block text-sm font-semibold text-gray-700 mb-1">
                    Vestiging
                </label>
                <select id="vestiging" name="vestiging" required class="ff-input">
                    <option value="">Kies een vestiging</option>
                    @foreach (config('vestigingen.locaties') as $vestiging)
                        <option value="{{ $vestiging }}" @selected(old('vestiging') === $vestiging)>
                            {{ $vestiging }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="naam" class="block text-sm font-semibold text-gray-700 mb-1">
                    Naam
                </label>
                <input
                    type="text"
                    id="naam"
                    name="naam"
                    value="{{ old('naam', auth()->user()?->name) }}"
                    required
                    class="ff-input"
                >
            </div>
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">
                    Email
                </label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email', auth()->user()?->email) }}"
                    required
                    class="ff-input"
                >
            </div>
            <div>
                <label for="telefoonnummer" class="block text-sm font-semibold text-gray-700 mb-1">
                    Telefoonnummer
                </label>
                <input
                    type="text"
                    id="telefoonnummer"
                    name="telefoonnummer"
                    value="{{ old('telefoonnummer') }}"
                    required
                    class="ff-input"
                >
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="fietstype" class="block text-sm font-semibold text-gray-700 mb-1">
                        Fietstype
                    </label>
                    <input
                        type="text"
                        id="fietstype"
                        name="fietstype"
                        value="{{ old('fietstype') }}"
                        required
                        placeholder="Bijv. E-bike"
                        class="ff-input"
                    >
                </div>
                <div>
                    <label for="fietsmerk" class="block text-sm font-semibold text-gray-700 mb-1">
                        Fietsmerk
                    </label>
                    <input
                        type="text"
                        id="fietsmerk"
                        name="fietsmerk"
                        value="{{ old('fietsmerk') }}"
                        required
                        placeholder="Bijv. Gazelle"
                        class="ff-input"
                    >
                </div>
            </div>
            <div>
                <label for="probleem" class="block text-sm font-semibold text-gray-700 mb-1">
                    Probleem
                </label>
                <textarea
                    id="probleem"
                    name="probleem"
                    rows="4"
                    required
                    placeholder="Beschrijf wat er mis is met je fiets..."
                    class="ff-input"
                >{{ old('probleem') }}</textarea>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="datum" class="block text-sm font-semibold text-gray-700 mb-1">
                    Datum
                    </label>
                    <input
                        type="date"
                        min="{{ today()->toDateString() }}"
                        id="datum"
                        name="datum"
                        value="{{ old('datum') }}"
                        required
                        class="ff-input"
                    >
                </div>
                <div>
                    <label for="tijd" class="block text-sm font-semibold text-gray-700 mb-1">
                        Tijd
                    </label>
                    {{-- Schakel de keuzelijst uit als de tijden nog niet zijn opgehaald of de dag vol is. --}}
                    <select id="tijd" name="tijd" required class="ff-input" @disabled(!$beschikbaarheidGeladen || $volgeboekt) aria-describedby="tijd-status">
                        <option value="">Kies een tijd</option>
                        @foreach (\App\Models\Afspraken::tijdstippen() as $tijdstip)
                            @php
                                // Controleer of dit tijdstip tussen de gereserveerde tijden staat.
                                $isBezet = in_array($tijdstip, $bezetteTijden);
                            @endphp
                            {{-- disabled maakt een bezet tijdstip onklikbaar; selected bewaart een eerdere vrije keuze. --}}
                            <option value="{{ $tijdstip }}" @disabled($isBezet) @selected(old('tijd') === $tijdstip && !$isBezet)>
                                {{ $tijdstip }}@if ($isBezet) (gereserveerd)@endif
                            </option>
                        @endforeach
                    </select>
                    <p id="tijd-status" role="status" class="mt-1 text-sm text-gray-500">
                        @if ($volgeboekt)
                            Deze dag is volgeboekt. Kies een andere datum of vestiging.
                        @elseif ($beschikbaarheidGeladen)
                            Tijden voor {{ old('vestiging') }} op {{ old('datum') }}. Gereserveerde tijden zijn niet beschikbaar.
                        @else
                            Kies een vestiging en datum en klik op 'Beschikbare tijden tonen'.
                        @endif
                    </p>
                </div>
            </div>
            {{-- Deze knop stuurt actie=beschikbaarheid mee. formnovalidate laat andere velden nog leeg;
                 de controller controleert bij deze stap alleen de vestiging en datum. --}}
            <button type="submit" name="actie" value="beschikbaarheid" formnovalidate
                class="w-full bg-gray-200 text-gray-800 font-bold py-3 rounded-lg">
                Beschikbare tijden tonen
            </button>
            <p class="text-sm text-gray-500">Klik na het wijzigen van de vestiging of datum opnieuw op 'Beschikbare tijden tonen'.</p>
            {{-- Deze knop maakt de afspraak. Hij is alleen beschikbaar als er vrije tijden zijn opgehaald. --}}
            <button
                type="submit"
                @disabled(!$beschikbaarheidGeladen || $volgeboekt)
                class="w-full bg-green-600 text-white font-bold py-3 rounded-lg
                hover:bg-green-700 transition shadow-sm"
            >
                🚲 Afspraak inplannen
            </button>
        </form>
    </div>
</div>
