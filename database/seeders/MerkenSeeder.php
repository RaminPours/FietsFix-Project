<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class MerkenSeeder extends Seeder
{
    public function run(): void
    {
        // Dit zijn demonstratieproducten, geen echte modellen of officiële merkprijzen.
        $voorbeelden = [
            ['Batavus', 'Stadsfiets', 79900],
            ['Giant', 'E-bike', 259900],
            ['Gazelle', 'E-bike', 279900],
            ['Sparta', 'E-bike', 239900],
            ['Stella', 'E-bike', 219900],
            ['Cortina', 'Stadsfiets', 89900],
            ['Trek', 'E-bike', 299900],
            ['Cube', 'Bakfiets', 429900],
        ];

        foreach ($voorbeelden as [$merk, $categorie, $prijs]) {
            // Zoek de lokale foto van dit merk in de lijst met fotobronnen.
            $illustratie = 'images/winkel/'.strtolower($merk).'.svg';
            $afbeelding = $illustratie;
            foreach (config('productfotos') as $bestand => $foto) {
                if ($foto['eigenaar'] === $merk) {
                    $afbeelding = $bestand;
                }
            }

            $product = Product::firstOrCreate(['naam' => $merk.' - voorbeeld '.$categorie], [
                'merk' => $merk,
                'categorie' => $categorie,
                'prijs_cent' => $prijs,
                'beschrijving' => 'Ontdek de categorie '.$categorie.' binnen ons '.$merk.'-assortiment. Dit is een voorbeeldartikel om de shop te demonstreren. Model, uitvoering en prijs moeten nog door de winkel worden ingevuld.',
                'afbeelding' => $afbeelding,
                'specificaties' => [
                    'Merk' => $merk,
                    'Type' => $categorie,
                    'Model' => 'Demonstratieartikel, geen officieel model',
                    'Uitvoering en framemaat' => 'Nog vast te stellen door de winkel',
                ],
            ]);

            // Vervang alleen onze oude illustratie. Een zelf ingestelde foto blijft behouden.
            if ($product->afbeelding === $illustratie) {
                $product->update(['afbeelding' => $afbeelding]);
            }
        }
    }
}
