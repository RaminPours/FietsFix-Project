<?php

namespace App\Http\Controllers;

use App\Models\Bestelling;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PersoneelController extends Controller
{
    public function index()
    {
        $openstaandeBestellingen = Bestelling::where('status', 'Ontvangen')->count();
        $lageVoorraad = Product::where('voorraad', '<=', 2)->orderBy('voorraad')->get();

        return view('personeel.index', compact('openstaandeBestellingen', 'lageVoorraad'));
    }

    public function voorraad()
    {
        $producten = Product::orderBy('naam')->get();

        return view('personeel.voorraad', compact('producten'));
    }

    public function voorraadAanvullen(Request $request, Product $product)
    {
        $gegevens = $request->validate([
            'aantal' => ['required', 'integer', 'min:1', 'max:1000'],
        ]);

        DB::transaction(function () use ($product, $gegevens) {
            Product::lockForUpdate()->findOrFail($product->id)
                ->increment('voorraad', $gegevens['aantal']);
        });

        return redirect()->route('personeel.voorraad')
            ->with('success', 'De voorraad is aangevuld.');
    }

    public function voorraadVerlagen(Request $request, Product $product)
    {
        $gegevens = $request->validate([
            'aantal' => ['required', 'integer', 'min:1', 'max:1000'],
        ]);

        DB::transaction(function () use ($product, $gegevens) {
            $product = Product::lockForUpdate()->findOrFail($product->id);

            if ($product->voorraad < $gegevens['aantal']) {
                throw ValidationException::withMessages([
                    'aantal' => 'Je kunt niet meer voorraad verwijderen dan er beschikbaar is.',
                ]);
            }

            $product->decrement('voorraad', $gegevens['aantal']);
        });

        return redirect()->route('personeel.voorraad')
            ->with('success', 'De voorraad is verlaagd.');
    }

    public function bestellingen()
    {
        $bestellingen = Bestelling::latest()->paginate(20);
        $statussen = Bestelling::STATUSSEN;

        return view('personeel.bestellingen', compact('bestellingen', 'statussen'));
    }

    public function bestellingStatusBijwerken(Request $request, Bestelling $bestelling)
    {
        $gegevens = $request->validate([
            'status' => ['required', Rule::in(Bestelling::STATUSSEN)],
        ]);

        $bestelling->update(['status' => $gegevens['status']]);

        return redirect()->route('personeel.bestellingen')
            ->with('success', 'De bestelstatus is bijgewerkt.');
    }
}
