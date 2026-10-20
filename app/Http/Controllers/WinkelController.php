<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WinkelController extends Controller
{
    public function index(Request $request)
    {
        // De gewone winkel toont alleen fietsen en mag ook op merk filteren.
        $categorieen = config('winkel.fietscategorieen');

        return $this->overzicht($request, $categorieen, config('winkel.merken'));
    }

    public function accessoires(Request $request)
    {
        // Accessoires hebben geen merkfilter; daarom geven we een lege merkenlijst mee.
        return $this->overzicht($request, config('winkel.accessoires'), [], true);
    }

    public function show(Product $product)
    {
        // Een uitgeschakeld product is niet zichtbaar in de winkel.
        abort_unless($product->actief, 404);

        return view('winkel.show', [
            'product' => $product,
            'accessoire' => in_array($product->categorie, config('winkel.accessoires')),
        ]);
    }

    private function overzicht(Request $request, array $categorieen, array $merken = [], bool $accessoires = false)
    {
        // De URL mag alleen bekende categorieën en merken bevatten.
        $regels = ['categorie' => ['nullable', Rule::in($categorieen)]];

        if ($merken) {
            $regels['merk'] = ['nullable', Rule::in($merken)];
        }

        $request->validate($regels);

        // Bouw de productlijst op. `when` voegt een filter alleen toe als het is ingevuld.
        $producten = Product::where('actief', true)
            ->whereIn('categorie', $categorieen)
            ->when($request->filled('categorie'), fn ($query) => $query->where('categorie', $request->input('categorie')))
            ->when($merken && $request->filled('merk'), fn ($query) => $query->where('merk', $request->input('merk')))
            ->orderBy('prijs_cent')
            ->paginate(12)
            ->withQueryString();

        return view('winkel.index', compact('producten', 'categorieen', 'merken', 'accessoires'));
    }
}