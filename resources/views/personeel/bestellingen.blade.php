@extends('layouts.app')

@section('content')
    <section class="ff-section max-w-6xl">
        <a href="{{ route('personeel.index') }}" class="ff-link">Terug naar personeelsbeheer</a>
        <div class="mt-8 border-b border-slate-200 pb-7">
            <p class="ff-eyebrow">Alleen voor personeel</p>
            <h1 class="ff-title">Bestellingen beheren</h1>
            <p class="mt-3 text-slate-600">Bekijk bestellingen en werk de bestelstatus bij.</p>
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

        <div class="mt-8 space-y-5">
            @forelse ($bestellingen as $bestelling)
                <article class="ff-card">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <p class="ff-eyebrow">Bestelling #{{ $bestelling->id }} · {{ $bestelling->created_at->format('d-m-Y H:i') }}</p>
                            <h2 class="mt-2 text-2xl font-bold text-slate-900">{{ $bestelling->product_naam }}</h2>
                            @if ($bestelling->bestelnummer)
                                <p class="mt-2 break-all text-sm text-slate-500">Bestelnummer: {{ $bestelling->bestelnummer }}</p>
                            @endif
                        </div>
                        <span class="rounded-xl bg-teal-50 px-3 py-2 text-sm font-bold text-teal-800">{{ $bestelling->status }}</span>
                    </div>

                    <dl class="mt-6 grid gap-4 border-y border-slate-200 py-5 text-sm sm:grid-cols-2 lg:grid-cols-4">
                        <div><dt class="font-semibold text-slate-500">Klant</dt><dd class="mt-1">{{ $bestelling->naam }}</dd></div>
                        <div><dt class="font-semibold text-slate-500">Contact</dt><dd class="mt-1 break-words">{{ $bestelling->email }}<br>{{ $bestelling->telefoonnummer }}</dd></div>
                        <div><dt class="font-semibold text-slate-500">Afhalen bij</dt><dd class="mt-1">{{ $bestelling->vestiging }}</dd></div>
                        <div><dt class="font-semibold text-slate-500">Aantal en totaal</dt><dd class="mt-1">{{ $bestelling->aantal }} × € {{ number_format($bestelling->prijs_cent / 100, 2, ',', '.') }}<br><strong>€ {{ number_format($bestelling->prijs_cent * $bestelling->aantal / 100, 2, ',', '.') }}</strong></dd></div>
                    </dl>

                    <form method="POST" action="{{ route('personeel.bestellingen.status', $bestelling) }}" class="mt-5 flex flex-wrap items-end gap-3">
                        @csrf
                        @method('PATCH')
                        <div>
                            <label for="status-{{ $bestelling->id }}" class="mb-1 block text-sm font-semibold">Bestelstatus</label>
                            <select id="status-{{ $bestelling->id }}" name="status" class="ff-input h-10 min-w-56 py-0" required>
                                @foreach ($statussen as $status)
                                    <option value="{{ $status }}" @selected($bestelling->status === $status)>{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="ff-primary h-10 px-4 py-0 text-xs">Status opslaan</button>
                    </form>
                </article>
            @empty
                <p class="ff-card text-slate-600">Er zijn nog geen bestellingen.</p>
            @endforelse
        </div>

        <div class="mt-8">{{ $bestellingen->links() }}</div>
    </section>
@endsection
