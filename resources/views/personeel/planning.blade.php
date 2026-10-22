@extends('layouts.app')

@section('content')
    <section class="ff-section max-w-6xl">
        <a href="{{ route('personeel.index') }}" class="ff-link">Terug naar personeelsbeheer</a>
        <div class="mt-8 border-b border-slate-200 pb-7">
            <p class="ff-eyebrow">Alleen voor personeel</p>
            <h1 class="ff-title">Werkplaatsplanning</h1>
            <p class="mt-3 text-slate-600">Bekijk alle aankomende afspraken per vestiging, gesorteerd op datum en tijd.</p>
        </div>

        @if ($errors->any())
            <ul role="alert" class="mt-6 list-disc rounded-xl bg-red-50 p-6 text-red-700">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
        @if (session('success'))
            <p role="status" class="mt-6 rounded-xl bg-teal-50 p-5 text-teal-800">{{ session('success') }}</p>
        @endif

        <form method="GET" action="{{ route('personeel.planning') }}" class="ff-card mt-8 flex flex-wrap items-end gap-4">
            <div>
                <label for="vestiging" class="mb-1 block text-sm font-semibold">Vestiging</label>
                <select id="vestiging" name="vestiging" class="ff-input h-10 min-w-52 py-0" required onchange="this.form.submit()">
                    @foreach ($vestigingen as $locatie)
                        <option value="{{ $locatie }}" @selected($locatie === $vestiging)>{{ $locatie }}</option>
                    @endforeach
                </select>
            </div>
        </form>

        <div class="mt-8 space-y-4">
            @forelse ($afspraken as $afspraak)
                <article class="ff-card">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <p class="ff-eyebrow">{{ date('d-m-Y', strtotime($afspraak->datum)) }} om {{ substr($afspraak->tijd, 0, 5) }} · {{ $afspraak->vestiging }}</p>
                            <h2 class="mt-2 text-xl font-bold text-slate-900">{{ $afspraak->fietstype }} · {{ $afspraak->fietsmerk }}</h2>
                            <p class="mt-1 text-sm text-slate-600">{{ $afspraak->type === 'proefrit' ? 'Proefrit' : 'Reparatieafspraak' }}</p>
                        </div>
                    </div>
                    <dl class="mt-5 grid gap-4 border-t border-slate-200 pt-4 text-sm sm:grid-cols-2">
                        <div><dt class="font-semibold text-slate-500">Klant</dt><dd class="mt-1">{{ $afspraak->naam }}</dd></div>
                        <div><dt class="font-semibold text-slate-500">Contact</dt><dd class="mt-1 break-words">{{ $afspraak->email }}<br>{{ $afspraak->telefoonnummer }}</dd></div>
                        <div class="sm:col-span-2"><dt class="font-semibold text-slate-500">Klacht of notitie</dt><dd class="mt-1 whitespace-pre-line">{{ $afspraak->probleem }}</dd></div>
                    </dl>
                </article>
            @empty
                <p class="ff-card text-slate-600">Er zijn geen afspraken voor deze selectie.</p>
            @endforelse
        </div>
    </section>
@endsection
