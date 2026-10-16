@extends('layouts.app')

@section('content')
    <section class="bg-teal-50">
        <div class="ff-section">
            <p class="ff-eyebrow">FietsFix Shop</p>
            <h1 class="ff-title">Plan een proefrit.</h1>
            <p class="mt-4 max-w-2xl text-slate-600">Kies een vestiging, datum en tijd. We zetten een fiets voor je klaar.</p>
        </div>
    </section>

    <section class="ff-section max-w-3xl">
        @if ($errors->any())
            <div role="alert" class="mb-6 rounded-lg bg-red-50 p-4 text-red-700">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('proefrit.store') }}" method="POST" class="space-y-5 rounded-2xl bg-white p-8 shadow-md">
            @csrf
            <div>
                <label for="vestiging" class="mb-1 block text-sm font-semibold text-gray-700">Vestiging</label>
                <select id="vestiging" name="vestiging" required class="ff-input">
                    <option value="">Kies een vestiging</option>
                    @foreach (config('vestigingen.locaties') as $vestiging)
                        <option value="{{ $vestiging }}" @selected(old('vestiging') === $vestiging)>{{ $vestiging }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label for="naam" class="mb-1 block text-sm font-semibold text-gray-700">Naam</label>
                    <input id="naam" name="naam" value="{{ old('naam', auth()->user()->name) }}" required class="ff-input">
                </div>
                <div>
                    <label for="email" class="mb-1 block text-sm font-semibold text-gray-700">E-mail</label>
                    <input type="email" id="email" name="email" value="{{ old('email', auth()->user()->email) }}" required class="ff-input">
                </div>
            </div>
            <div>
                <label for="telefoonnummer" class="mb-1 block text-sm font-semibold text-gray-700">Telefoonnummer</label>
                <input id="telefoonnummer" name="telefoonnummer" value="{{ old('telefoonnummer') }}" required class="ff-input">
            </div>
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label for="datum" class="mb-1 block text-sm font-semibold text-gray-700">Datum</label>
                    <input type="date" id="datum" name="datum" min="{{ today()->toDateString() }}" value="{{ old('datum') }}" required class="ff-input">
                </div>
                <div>
                    <label for="tijd" class="mb-1 block text-sm font-semibold text-gray-700">Tijd</label>
                    <select id="tijd" name="tijd" required class="ff-input">
                        <option value="">Kies een tijd</option>
                        @foreach (\App\Models\Afspraken::tijdstippen() as $tijdstip)
                            <option value="{{ $tijdstip }}" @selected(old('tijd') === $tijdstip)>{{ $tijdstip }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <button class="ff-primary" type="submit">Proefrit inplannen</button>
        </form>
    </section>
@endsection
