@extends('layouts.app')
@section('content')
    <section class="ff-section">
        <p class="ff-eyebrow">Jouw account</p>
        <h1 class="ff-title">Mijn bestellingen</h1>
        @if (session('success'))
            <p role="status" class="mt-6 rounded-xl bg-teal-50 p-5 text-teal-800">{{ session('success') }}</p>
        @endif
        @if ($errors->any())
            <div role="alert" class="mt-6 rounded-xl bg-red-50 p-5 text-red-700">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
        <div class="mt-8 space-y-6">
            @forelse ($bestellingen as $bestelling)
                <article class="ff-card">
                    <p class="ff-eyebrow">Bestelling #{{ $bestelling->id }} · {{ $bestelling->created_at->format('d-m-Y') }}</p>
                    <h2 class="mt-3 text-2xl font-bold">{{ $bestelling->product_naam }}</h2>
                    @if ($bestelling->bestelnummer)
                        <p class="mt-2 break-all text-sm text-slate-500">Bestelnummer: {{ $bestelling->bestelnummer }}</p>
                    @endif
                    <dl class="mt-5 grid gap-4 sm:grid-cols-2">
                        <div><dt class="font-semibold">Aantal</dt><dd>{{ $bestelling->aantal }}</dd></div>
                        <div><dt class="font-semibold">Totaal</dt><dd>&euro; {{ number_format($bestelling->prijs_cent * $bestelling->aantal / 100, 2, ',', '.') }}</dd></div>
                        <div><dt class="font-semibold">Afhalen bij Fietsfix in:</dt><dd>{{ $bestelling->vestiging }}</dd></div>
                        <div><dt class="font-semibold">Status</dt><dd>{{ $bestelling->status }}</dd></div>
                    </dl>
                    <p class="mt-5 text-sm text-slate-600">Betalen in de winkel. Wacht op bevestiging voordat je het artikel ophaalt.</p>
                    {{-- Alleen ontvangen artikelen kunnen nog worden geannuleerd. --}}
                    @if ($bestelling->status === 'Ontvangen')
                        <form method="POST" action="{{ route('bestellingen.annuleren', $bestelling->id) }}" class="mt-5">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="ff-secondary">Annuleren</button>
                            <p class="mt-2 text-sm text-slate-500">Je annuleert en verwijdert hiermee dit artikel, inclusief het bestelde aantal.</p>
                        </form>
                    @endif
                </article>
            @empty
                <p>Je hebt geen producten besteld.</p>
            @endforelse
        </div>
        <div class="mt-8">{{ $bestellingen->links() }}</div>
        <a href="{{ route('winkel.index') }}" class="ff-primary mt-8">Bekijk de fietsen</a>
    </section>
@endsection
