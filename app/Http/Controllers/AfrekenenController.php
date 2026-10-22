<?php

namespace App\Http\Controllers;

use App\Models\Bestelling;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AfrekenenController extends Controller
{
    public function create(Request $request)
    {
        // Het mandje in de sessie bevat alleen product-ID's en aantallen.
        $mandje = $request->session()->get('winkelmand', []);
        $producten = Product::whereIn('id', array_keys($mandje))->where('actief', true)->get();

        if (empty($mandje) || count($producten) !== count($mandje)) {
            return redirect()->route('winkelmand.index')->withErrors(['mandje' => 'Controleer je winkelmandje. Het is leeg of bevat een niet meer beschikbaar artikel.']);
        }

        // Bewaar wat de klant op deze pagina ziet om latere wijzigingen te herkennen.
        $checkout = [
            'nummer' => (string) Str::uuid(),
            'user_id' => $request->user()->id,
            'mandje' => $mandje,
            'regels' => [],
        ];
        $totaal = 0;
        foreach ($producten as $product) {
            if ($product->voorraad < $mandje[$product->id]) {
                return redirect()->route('winkelmand.index')->withErrors(['mandje' => $product->naam.' is onvoldoende op voorraad.']);
            }
            $checkout['regels'][$product->id] = [
                'prijs_cent' => $product->prijs_cent,
                'formuliernummer' => (string) Str::uuid(),
            ];
            $totaal += $product->prijs_cent * $mandje[$product->id];
        }
        $request->session()->put('winkel_checkout', $checkout);

        return view('winkelmand.afrekenen', [
            'producten' => $producten, 'mandje' => $mandje,
            'totaal' => $totaal, 'formuliernummer' => $checkout['nummer'],
        ]);
    }

    public function store(Request $request)
    {
        // Valideer alleen gegevens die de klant zelf mag invullen.
        $gegevens = $request->validate([
            'naam' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'telefoonnummer' => ['required', 'string', 'max:20'],
            'vestiging' => ['required', Rule::in(config('vestigingen.locaties'))],
            'formuliernummer' => ['required', 'uuid'],
        ]);

        // Bij opnieuw versturen van een afgerond formulier maken we niets extra aan.
        if (Bestelling::where('bestelnummer', $gegevens['formuliernummer'])
            ->where('user_id', $request->user()->id)->exists()) {
            return redirect()->route('bestellingen.index');
        }

        // Vergelijk met de gegevens die bij het openen van de afrekenpagina zijn bewaard.
        $checkout = $request->session()->get('winkel_checkout');
        if (!$checkout || $checkout['nummer'] !== $gegevens['formuliernummer']
            || $checkout['user_id'] !== $request->user()->id
            || $checkout['mandje'] !== $request->session()->get('winkelmand', [])) {
            return redirect()->route('winkelmand.index')->withErrors(['mandje' => 'Je winkelmandje is gewijzigd. Controleer het en ga opnieuw door naar bestellen.']);
        }

        // Een transactie slaat alle artikelen samen op, of helemaal niets bij een fout.
        DB::transaction(function () use ($request, $gegevens, $checkout) {
            if (Bestelling::where('bestelnummer', $gegevens['formuliernummer'])
                ->where('user_id', $request->user()->id)->exists()) {
                return;
            }
            $producten = Product::whereIn('id', array_keys($checkout['mandje']))
                ->orderBy('id')->lockForUpdate()->get();

            if (count($producten) !== count($checkout['mandje'])) {
                throw ValidationException::withMessages(['mandje' => 'Een artikel is niet meer beschikbaar. Controleer je winkelmandje.']);
            }

            foreach ($producten as $product) {
                if (!$product->actief || $product->prijs_cent !== $checkout['regels'][$product->id]['prijs_cent']) {
                    throw ValidationException::withMessages(['mandje' => 'Een artikel of prijs is gewijzigd. Controleer je winkelmandje opnieuw.']);
                }
                if ($product->voorraad < $checkout['mandje'][$product->id]) {
                    throw ValidationException::withMessages(['mandje' => $product->naam.' is onvoldoende op voorraad.']);
                }

                // Prijs, product en aantal komen uit de database en de sessie, niet uit verborgen velden.
                Bestelling::create([
                    'formuliernummer' => $checkout['regels'][$product->id]['formuliernummer'],
                    'bestelnummer' => $checkout['nummer'],
                    'user_id' => $request->user()->id,
                    'product_id' => $product->id,
                    'product_naam' => $product->naam,
                    'prijs_cent' => $product->prijs_cent,
                    'aantal' => $checkout['mandje'][$product->id],
                    'naam' => $gegevens['naam'],
                    'email' => $gegevens['email'],
                    'telefoonnummer' => $gegevens['telefoonnummer'],
                    'vestiging' => $gegevens['vestiging'],
                    'status' => 'Ontvangen',
                ]);
                $product->decrement('voorraad', $checkout['mandje'][$product->id]);
            }
        });

        // Maak het mandje pas leeg nadat alle artikelen succesvol zijn opgeslagen.
        $request->session()->forget(['winkelmand', 'winkel_checkout']);

        return redirect()->route('bestellingen.index')->with('success', 'Je bestelling is ontvangen. Je betaalt bij het afhalen. Wacht op bevestiging van de winkel.');
    }
}
