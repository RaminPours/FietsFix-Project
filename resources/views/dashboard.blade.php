@extends('layouts.app')
@section('content')
    <div class="max-w-6xl mx-auto px-4 py-10 sm:px-6">
        <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    Mijn afspraken
                </h1>
                <p class="mt-2 text-gray-600">
                    Welkom, {{ auth()->user()->name }}. Bekijk hier je afspraken bij FietsFix.
                </p>
            </div>
            <a href="{{ route('afspraken.create') }}" class="rounded-lg bg-green-600 px-5 py-3 font-bold text-white hover:bg-green-700">
                Nieuwe afspraak
            </a>
        </div>
        @if (session('success'))
            <div role="status" class="mb-6 rounded-lg bg-green-50 p-4 text-green-800">
                {{ session('success') }}
            </div>
        @endif
        <div>
            @forelse ($afspraken as $afspraak)
                <article class="min-w-0 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <div class="flex flex-col-reverse gap-6 sm:flex-row sm:items-start">
                        {{-- Afspraakgegevens links --}}
                        <div class="min-w-0 flex-1">
                            <h2 class="text-xl font-bold text-gray-900">
                                {{ $afspraak->type === 'proefrit' ? 'Proefrit' : 'Fietsmerk: '.$afspraak->fietsmerk.' · ('.$afspraak->fietstype.')' }}
                            </h2>
                            <p class="mt-2 font-semibold text-green-700">
                                Uw afspraak is op:
                                                {{ \Illuminate\Support\Carbon::parse($afspraak->datum)->format('d-m-Y') }}
                                                om {{ substr($afspraak->tijd, 0, 5) }}
                            </p>
                            <dl class="mt-6 space-y-3 break-words text-gray-700">
                                <div>
                                    <dt class="font-semibold">Vestiging</dt>
                                    <dd>{{ $afspraak->vestiging ?? 'Niet opgegeven' }}</dd>
                                </div>
                                <div>
                                    <dt class="font-semibold">
                                        {{ $afspraak->type === 'proefrit' ? 'Proefrit aangevraagd' : 'Probleem' }}
                                    </dt>
                                    <dd class="whitespace-pre-line">
                                        {{ $afspraak->type === 'proefrit' ? 'Uw proefrit is aangevraagd.' : $afspraak->probleem }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="font-semibold">
                                        Naam
                                    </dt>
                                    <dd>
                                        {{ $afspraak->naam }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="font-semibold">
                                        E-mail
                                    </dt>
                                    <dd>
                                        {{ $afspraak->email }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="font-semibold">
                                        Telefoonnummer
                                    </dt>
                                    <dd>
                                        {{ $afspraak->telefoonnummer }}
                                    </dd>
                                </div>
                            </dl>
                            <p class="mt-6 text-sm text-gray-500">
                                {{ $afspraak->type === 'proefrit' ? 'Uw fiets staat klaar!' : 'Wij bellen u wanneer uw fiets klaar is.' }}
                            </p>
                            <form
                                action="{{ route('afspraken.delete', $afspraak->id) }}"
                                method="POST"
                                class="mt-4"
                                onsubmit="return confirm('Weet je zeker dat je deze afspraak wilt verwijderen?')"
                            >
                                @csrf
                                @method('DELETE')
                                <button
                                    type="submit"
                                    class="rounded-lg border border-red-600 px-4 py-2 font-semibold text-red-700 hover:bg-red-50"
                                >
                                    Afspraak verwijderen
                                </button>
                            </form>
                        </div>
                        <div class="w-full shrink-0 sm:w-3/5">
                            <img
                                src="{{ asset($afspraak->type === 'proefrit' ? 'images/proefrit-fiets.png' : 'images/repair2.jpg') }}"
                                alt="{{ $afspraak->type === 'proefrit' ? 'Persoon op een fiets tijdens een proefrit' : 'FietsFix onderhoud' }}"
                                class="w-full rounded-xl object-cover"
                            >
                        </div>
                    </div>
                </article>
            @empty
                <div class="rounded-2xl border border-gray-200 bg-gray-50 p-8 md:col-span-2">
                    <h2 class="text-xl font-bold text-gray-900">
                        Je hebt nog geen afspraken.
                    </h2>
                    <p class="mt-2 text-gray-600">
                        Klik op Nieuwe afspraak om je eerste afspraak in te plannen.
                    </p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
