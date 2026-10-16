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
        // Producten die niet actief zijn, kunnen niet besteld worden.
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
        $bestelling = $this->bestellingVanGebruiker($request, $id);

        // Controleer de status ook bij het verwijderen, voor het geval die ondertussen verandert.
        $verwijderd = Bestelling::whereKey($bestelling->id)
            ->where('status', 'Ontvangen')
            ->delete();

        if (!$verwijderd) {
            return redirect()->route('bestellingen.index')
                ->withErrors(['annuleren' => 'Dit artikel is al geannuleerd of de bestelling is al in behandeling.']);
        }
        return redirect()->route('winkel.index')
            ->with('success', 'Je bestelling is geannuleerd.');
    }   

    public function store(Request $request, Product $product)
    {
        abort_unless($product->actief, 404);

        // Controleer de gegevens die de klant in het formulier heeft ingevuld.
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

    private function bestellingVanGebruiker(Request $request, int $id): Bestelling
    {
        // Deze helper voorkomt dat dezelfde controle op eigenaarschap op meerdere plekken staat.
        return Bestelling::where('user_id', $request->user()->id)->findOrFail($id);
    }
}
