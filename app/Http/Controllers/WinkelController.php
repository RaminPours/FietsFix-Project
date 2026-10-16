<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WinkelController extends Controller
{
    public function index(Request $request)
    {
        $categorieen = config('winkel.fietscategorieen');
        return $this->overzicht($request, $categorieen, config('winkel.merken'));
    }

    public function accessoires(Request $request)
    {
        return $this->overzicht($request, config('winkel.accessoires'), [], true);
    }

    public function show(Product $product)
    {
        abort_unless($product->actief, 404);

        return view('winkel.show', [
            'product' => $product,
            'accessoire' => in_array($product->categorie, config('winkel.accessoires')),
        ]);
    }

    private function overzicht(Request $request, array $categorieen, array $merken = [], bool $accessoires = false)
    {
        $regels = ['categorie' => ['nullable', Rule::in($categorieen)]];

        if ($merken) {
            $regels['merk'] = ['nullable', Rule::in($merken)];
        }

        $request->validate($regels);

        // Een gewoon GET-formulier filtert de producten.
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