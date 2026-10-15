<?php

namespace App\Http\Controllers;

use App\Models\Bestelling;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BestellingController extends Controller
{
    public function index(Request $request)
    {
        // Klanten kunnen alleen hun eigen bestellingen bekijken.
        $bestellingen = Bestelling::where('user_id', $request->user()->id)->latest()->paginate(10);

        return view('bestellingen.index', ['bestellingen' => $bestellingen]);
    }

    public function create(Request $request, Product $product)
    {
        abort_unless($product->actief, 404);

        // Elk bestelformulier krijgt een eigen nummer tegen dubbel versturen.
        $formuliernummer = (string) Str::uuid();
        $request->session()->put('bestelformulieren.'.$formuliernummer, $product->id);

        return view('bestellingen.create', [
            'product' => $product,
            'formuliernummer' => $formuliernummer,
        ]);
    }

    public function annuleren(Request $request, int $id)
    {
        // Zoek alleen in de bestellingen van de ingelogde klant.
        $bestelling = Bestelling::where('user_id', $request->user()->id)
            ->whereKey($id)
            ->firstOrFail();

        // Controleer de status ook bij het bijwerken, voor het geval die ondertussen verandert.
        $aangepast = Bestelling::whereKey($bestelling->id)
            ->where('status', 'Ontvangen')
            ->update(['status' => 'Geannuleerd']);

        if (!$aangepast) {
            return redirect()->route('bestellingen.index')
                ->withErrors(['annuleren' => 'Dit artikel is al geannuleerd of de bestelling is al in behandeling.']);
        }
        // Bewaar het artikel in het overzicht met de nieuwe status.
        return redirect()->route('bestellingen.index')
            ->with('success', 'Het bestelde artikel is geannuleerd.');
    }

    public function verwijderen(Request $request, int $id)
    {
        // Zoek alleen in de bestellingen van de ingelogde klant.
        $bestelling = Bestelling::where('user_id', $request->user()->id)
            ->whereKey($id)
            ->firstOrFail();

        // Alleen geannuleerde bestellingen mogen verwijderd worden.
        if ($bestelling->status !== 'Geannuleerd') {
            return redirect()->route('bestellingen.index')
                ->withErrors(['verwijderen' => 'Je kunt alleen geannuleerde bestellingen verwijderen.']);
        }

        $bestelling->delete();

        return redirect()->route('bestellingen.index')
            ->with('success', 'De bestelling is verwijderd.');
    }   

    public function store(Request $request, Product $product)
    {
        abort_unless($product->actief, 404);
        $gegevens = $request->validate([
            'naam' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'telefoonnummer' => ['required', 'string', 'max:20'],
            'vestiging' => ['required', Rule::in(config('vestigingen.locaties'))],
            'aantal' => ['required', 'integer', 'min:1', 'max:5'],
            'formuliernummer' => ['required', 'uuid'],
        ]);

        // Alleen een formulier dat voor dit product is geopend mag worden gebruikt.
        abort_unless(
            $request->session()->get('bestelformulieren.'.$gegevens['formuliernummer']) === $product->id,
            419
        );

        // Neem de prijs uit de database, nooit uit een door de klant aangepast formulierveld.
        Bestelling::firstOrCreate(['formuliernummer' => $gegevens['formuliernummer']], [
            'user_id' => $request->user()->id,
            'product_id' => $product->id,
            'product_naam' => $product->naam,
            'prijs_cent' => $product->prijs_cent,
            'aantal' => $gegevens['aantal'],
            'naam' => $gegevens['naam'],
            'email' => $gegevens['email'],
            'telefoonnummer' => $gegevens['telefoonnummer'],
            'vestiging' => $gegevens['vestiging'],
            'status' => 'Ontvangen',
        ]);

        return redirect()->route('bestellingen.index')
            ->with('success', 'Je bestelling is ontvangen. Je hebt nog niet betaald. Wacht op bevestiging van de winkel voordat je de fiets ophaalt.');
    }

}