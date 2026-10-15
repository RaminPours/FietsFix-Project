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

        $categorieInformatie = [
            'Stadsfiets' => [
                'beschrijving' => 'Een comfortabel voorbeeldmodel voor dagelijkse ritten in de stad. De rechtopgerichte zithouding geeft overzicht in het verkeer en maakt korte ritten naar werk, school of winkels prettig. Controleer bij een bezoek aan de winkel welke framemaat, versnellingen en accessoires het best bij je passen.',
                'specificaties' => ['Rijhouding' => 'Rechtop en comfortabel', 'Geschikt voor' => 'Dagelijkse ritten in de stad', 'Let op' => 'Definitieve uitvoering en framemaat worden in de winkel bepaald'],
            ],
            'E-bike' => [
                'beschrijving' => 'Een elektrisch voorbeeldmodel voor woon-werkverkeer en recreatieve ritten. Trapondersteuning maakt optrekken en langere afstanden aangenamer. De uiteindelijke actieradius hangt onder meer af van de accu, gekozen ondersteuning, belading, bandenspanning, route en weersomstandigheden.',
                'specificaties' => ['Gebruik' => 'Woon-werkverkeer en recreatieve tochten', 'Trapondersteuning' => 'Kies een stand die past bij route en inspanning', 'Let op' => 'Accu, actieradius en uitvoering worden in de winkel bevestigd'],
            ],
            'Bakfiets' => [
                'beschrijving' => 'Een elektrisch voorbeeldmodel met ruimte voor boodschappen, bagage of gezinsritten. De ondersteuning helpt bij het optrekken en rijden met extra gewicht. Laat je in de winkel adviseren over veilige zitplaatsen, belading, een regenhuif en andere accessoires voor de voorbak.',
                'specificaties' => ['Gebruik' => 'Gezinsritten, boodschappen en bagage', 'Bak' => 'Geschikt voor lading; inrichting wordt in de winkel besproken', 'Let op' => 'Veilig gebruik en definitieve uitvoering worden in de winkel bevestigd'],
            ],
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
                'beschrijving' => $categorieInformatie[$categorie]['beschrijving'].' Dit is een voorbeeldartikel binnen ons '.$merk.'-assortiment; model, uitvoering en prijs moeten nog door de winkel worden bevestigd.',
                'afbeelding' => $afbeelding,
                'specificaties' => [
                    'Merk' => $merk,
                    'Type' => $categorie,
                    'Model' => 'Demonstratieartikel, geen officieel model',
                    ...$categorieInformatie[$categorie]['specificaties'],
                ],
            ]);

            $product->update([
                'beschrijving' => $categorieInformatie[$categorie]['beschrijving'].' Dit is een voorbeeldartikel binnen ons '.$merk.'-assortiment; model, uitvoering en prijs moeten nog door de winkel worden bevestigd.',
                'specificaties' => [
                    'Merk' => $merk,
                    'Type' => $categorie,
                    'Model' => 'Demonstratieartikel, geen officieel model',
                    ...$categorieInformatie[$categorie]['specificaties'],
                ],
            ]);

            // Vervang alleen onze oude illustratie. Een zelf ingestelde foto blijft behouden.
            if ($product->afbeelding === $illustratie) {
                $product->update(['afbeelding' => $afbeelding]);
            }
        }
    }
}
