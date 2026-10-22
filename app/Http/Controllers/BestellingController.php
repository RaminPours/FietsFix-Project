<?php

namespace App\Http\Controllers;

use App\Models\Bestelling;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

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
        abort_unless($product->actief && $product->voorraad > 0, 404);

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
        // Vergrendel bestelling en product samen, zodat voorraad maar één keer wordt teruggezet.
        $verwijderd = DB::transaction(function () use ($request, $id) {
            $bestelling = Bestelling::where('user_id', $request->user()->id)
                ->lockForUpdate()
                ->findOrFail($id);

            if ($bestelling->status !== 'Ontvangen') {
                return false;
            }

            // Oude bestellingen zonder product-ID kunnen wel worden verwijderd, maar hebben geen voorraad om aan te vullen.
            if ($bestelling->product_id && ($product = Product::lockForUpdate()->find($bestelling->product_id))) {
                $product->increment('voorraad', $bestelling->aantal);
            }

            $bestelling->delete();

            return true;
        });

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

        // Controleer én verlaag de voorraad in één transactie, zodat de laatste fiets niet dubbel wordt verkocht.
        DB::transaction(function () use ($request, $gegevens, $product) {
            $product = Product::lockForUpdate()->findOrFail($product->id);

            if (Bestelling::where('formuliernummer', $gegevens['formuliernummer'])->exists()) {
                return;
            }
            if (!$product->actief || $product->voorraad < $gegevens['aantal']) {
                throw ValidationException::withMessages(['aantal' => 'Er zijn onvoldoende exemplaren van dit product op voorraad.']);
            }

            Bestelling::create([
                'formuliernummer' => $gegevens['formuliernummer'],
                // Een directe bestelling bestaat uit één artikel; het formuliernummer kan daarom ook als bestelnummer dienen.
                'bestelnummer' => $gegevens['formuliernummer'],
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
            $product->decrement('voorraad', $gegevens['aantal']);
        });

        return redirect()->route('bestellingen.index')
            ->with('success', 'Je bestelling is ontvangen. Je hebt nog niet betaald. Wacht op bevestiging van de winkel voordat je de fiets ophaalt.');
    }
}
