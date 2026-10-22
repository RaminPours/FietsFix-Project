<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class WinkelmandController extends Controller
{
    public function index(Request $request)
    {
        // De sessie bewaart alleen productnummers en aantallen, nooit prijzen.
        $mandje = $request->session()->get('winkelmand', []);
        $producten = Product::whereIn('id', array_keys($mandje))->get();
        $totaal = 0;
        $kanBestellen = count($mandje) > 0 && count($producten) === count($mandje);

        foreach ($producten as $product) {
            $totaal += $product->prijs_cent * $mandje[$product->id];
            if (!$product->actief || $product->voorraad < $mandje[$product->id]) {
                $kanBestellen = false;
            }
        }

        return view('winkelmand.index', compact('mandje', 'producten', 'totaal', 'kanBestellen'));
    }

    public function toevoegen(Request $request, Product $product)
    {
        // Voeg geen producten toe die niet meer verkocht worden.
        abort_unless($product->actief, 404);
        $mandje = $request->session()->get('winkelmand', []);
        $aantal = $mandje[$product->id] ?? 0;

        if ($aantal >= min(5, $product->voorraad)) {
            return redirect()->route('winkelmand.index')->withErrors(['aantal' => 'Er zijn niet meer exemplaren van dit product op voorraad.']);
        }

        $mandje[$product->id] = $aantal + 1;
        $this->bewaarMandje($request, $mandje);

        return redirect()->route('winkelmand.index')->with('success', 'Het artikel is toegevoegd aan je winkelmandje.');
    }

    public function wijzigen(Request $request, int $product)
    {
        // Controleer eerst of het ingevoerde aantal binnen de toegestane grens valt.
        $gegevens = $request->validate(['aantal' => ['required', 'integer', 'min:1', 'max:5']]);
        $mandje = $request->session()->get('winkelmand', []);
        abort_unless(isset($mandje[$product]), 404);

        $productModel = Product::findOrFail($product);
        if (!$productModel->actief || $gegevens['aantal'] > $productModel->voorraad) {
            return redirect()->route('winkelmand.index')->withErrors(['aantal' => 'Er zijn onvoldoende exemplaren van dit product op voorraad.']);
        }

        $mandje[$product] = (int) $gegevens['aantal'];
        $this->bewaarMandje($request, $mandje);

        return redirect()->route('winkelmand.index');
    }

    public function verwijderen(Request $request, int $product)
    {
        // Verwijderen werkt ook als het product intussen uit de database is verwijderd.
        $mandje = $request->session()->get('winkelmand', []);
        unset($mandje[$product]);
        $this->bewaarMandje($request, $mandje);

        return redirect()->route('winkelmand.index');
    }
    private function bewaarMandje(Request $request, array $mandje): void
    {
        // Bij een wijziging is een eerder voorbereid afrekenformulier niet meer geldig.
        $request->session()->put('winkelmand', $mandje);
        $request->session()->forget('winkel_checkout');
    }
}
