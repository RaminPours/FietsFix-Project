@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-10 sm:px-6">
        <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Mijn afspraken</h1>
                <p class="mt-2 text-gray-600">Welkom, {{ auth()->user()->name }}. Bekijk hier je afspraken bij FietsFix.</p>
            </div>
            <a href="{{ route('afspraken.create') }}" class="rounded-lg bg-green-600 px-5 py-3 font-bold text-white hover:bg-green-700">Nieuwe afspraak</a>
        </div>
        @if (session('success'))
            <div role="status" class="mb-6 rounded-lg bg-green-50 p-4 text-green-800">{{ session('success') }}</div>
        @endif
        <div class="grid gap-6 md:grid-cols-2">
            @forelse ($afspraken as $afspraak)
                <article class="min-w-0 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <section class="relative">
                        <img
                            src="{{ asset('images/fietsfix.jpg') }}"
                            alt="FietsFix"
                            class="w-full h-[100px] object-cover"
                        >
                        <div class="absolute inset-0 bg-black/40"></div>
                     </section>
                    <h2 class="text-xl font-bold text-gray-900">{{ $afspraak->fietsmerk }} · {{ $afspraak->fietstype }}</h2>
                    <p class="mt-2 font-semibold text-green-700">Uw afspraak: {{ \Illuminate\Support\Carbon::parse($afspraak->datum)->format('d-m-Y') }} om {{ substr($afspraak->tijd, 0, 5) }}</p>
                    <dl class="mt-6 space-y-3 break-words text-gray-700">
                        <div><dt class="font-semibold">Probleem</dt><dd class="whitespace-pre-line">{{ $afspraak->probleem }}</dd></div>
                        <div><dt class="font-semibold">Naam</dt><dd>{{ $afspraak->naam }}</dd></div>
                        <div><dt class="font-semibold">E-mail</dt><dd>{{ $afspraak->email }}</dd></div>
                        <div><dt class="font-semibold">Telefoonnummer</dt><dd>{{ $afspraak->telefoonnummer }}</dd></div>
                    </dl>
                    <p class="mt-6 text-sm text-gray-500">Wij bellen u wanneer uw fiets klaar is.</p>
                    <form action="{{ route('afspraken.delete', $afspraak->id) }}" method="POST" class="mt-4" onsubmit="return confirm('Weet je zeker dat je deze afspraak wilt verwijderen?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="rounded-lg border border-red-600 px-4 py-2 font-semibold text-red-700 hover:bg-red-50">Afspraak verwijderen</button>
                    </form>
                </article>
            @empty
                <div class="rounded-2xl border border-gray-200 bg-gray-50 p-8 md:col-span-2">
                    <h2 class="text-xl font-bold text-gray-900">Je hebt nog geen afspraken.</h2>
                    <p class="mt-2 text-gray-600">Klik op Nieuwe afspraak om je eerste afspraak in te plannen.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
