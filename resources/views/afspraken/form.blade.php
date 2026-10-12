    <div class="max-w-2xl mx-auto px-6 py-10">

        <div class="bg-white rounded-2xl shadow-md p-8">

            <h1 class="text-3xl font-bold text-gray-800 mb-2">
                Afspraak inplannen 🚲
            </h1>

            <p class="text-gray-500 mb-8">
                Vul hieronder je gegevens in om een afspraak bij FietsFix te maken.
            </p>
            
            @if ($errors->any())
                <div role="alert" class="mb-6 rounded-lg bg-red-50 p-4 text-red-700">
                    <p class="font-semibold">Controleer je gegevens:</p>
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('afspraken.store') }}" method="POST" class="space-y-5">
                @csrf

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
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5
                               focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none"
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
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5
                               focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none"
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
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5
                               focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none"
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
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5
                                   focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none"
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
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5
                                   focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none"
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
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5
                               focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none"
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
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5
                                   focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none"
                        >
                    </div>

                    <div>
                        <label for="tijd" class="block text-sm font-semibold text-gray-700 mb-1">
                            Tijd
                        </label>
                        <input
                        type="time"
                        id="tijd"
                        name="tijd"
                        value="{{ old('tijd') }}"
                        min="08:00"
                        max="17:00"
                        required
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5
                            focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none"
                    >
                    </div>
                </div>

                <button
                    type="submit"
                    class="w-full bg-green-600 text-white font-bold py-3 rounded-lg
                           hover:bg-green-700 transition shadow-sm"
                >
                    🚲 Afspraak inplannen
                </button>

            </form>
        </div>

    </div>

