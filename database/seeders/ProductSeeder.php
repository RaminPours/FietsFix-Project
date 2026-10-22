<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Vul prijzen hier in euro's in, bijvoorbeeld 699.00 voor € 699,00.
        // De database bewaart het bedrag daarna veilig als hele centen.
        $producten = [
            [
                'naam' => 'FietsFix Stad', 'categorie' => 'Stadsfiets', 'prijs_euro' => 699.00,
                'beschrijving' => 'Een comfortabele stadsfiets voor dagelijkse ritten naar school, werk of de winkel. De lage instap maakt op- en afstappen gemakkelijk en de rechte zithouding geeft goed overzicht in het verkeer. Met zeven versnellingen kies je prettig een tempo op vlakke wegen en bij lichte tegenwind. Spatborden, verlichting en een bagagedrager maken deze fiets praktisch voor dagelijks gebruik.',
                'afbeelding' => 'images/winkel/stadsfiets.png',
                'specificaties' => ['Frame' => 'Aluminium, lage instap', 'Framemaat' => '53 cm', 'Rijhouding' => 'Rechtop en comfortabel', 'Versnellingen' => '7', 'Remmen' => 'Rollerbrakes', 'Verlichting' => 'Voor- en achterlicht', 'Bagagedrager' => 'Achterdrager voor tas of fietstas', 'Kleur' => 'Zeegroen', 'Gebruik' => 'Dagelijkse ritten in de stad'], 'voorraad' => 5,
            ],
            [
                'naam' => 'FietsFix Tour', 'categorie' => 'Stadsfiets', 'prijs_euro' => 999.00,
                'beschrijving' => 'Een sportieve stadsfiets voor dagelijkse ritten en langere tochten. Met acht versnellingen, een voor- en achterdrager en heldere verlichting neem je comfortabel spullen mee naar werk, school of de stad.',
                'afbeelding' => 'images/winkel/fietsfix-tour.png',
                'specificaties' => ['Frame' => 'Aluminium, hoge instap', 'Framemaat' => '56 cm', 'Rijhouding' => 'Actief en comfortabel', 'Versnellingen' => '8', 'Remmen' => 'V-brakes', 'Verlichting' => 'Voor- en achterlicht', 'Bagagedrager' => 'Voor- en achterdrager', 'Kleur' => 'Bordeauxrood', 'Gebruik' => 'Stad en langere tochten'], 'voorraad' => 5,
            ],
            [
                'naam' => 'FietsFix Elektrisch', 'categorie' => 'E-bike', 'prijs_euro' => 2499.00,
                'beschrijving' => 'Een elektrische fiets met trapondersteuning voor woon-werkverkeer en ontspannen tochten. De middenmotor ondersteunt natuurlijk tijdens het optrekken en op hellingen, terwijl de uitneembare accu thuis of op het werk kan worden opgeladen. De lage instap en comfortabele zithouding maken de fiets geschikt voor dagelijks gebruik. De werkelijke actieradius hangt af van de gekozen ondersteuning, belading, bandenspanning, route en het weer.',
                'afbeelding' => 'images/winkel/e-bike.png',
                'specificaties' => ['Frame' => 'Aluminium, lage instap', 'Framemaat' => '53 cm', 'Rijhouding' => 'Comfortabel en rechtop', 'Motor' => 'Middenmotor, 250 W', 'Accu' => '500 Wh, uitneembaar', 'Opladen' => 'Thuis of op het werk met meegeleverde lader', 'Versnellingen' => '8', 'Remmen' => 'Hydraulische schijfremmen', 'Gebruik' => 'Woon-werkverkeer en recreatieve tochten', 'Kleur' => 'Donkerblauw'], 'voorraad' => 5,
            ],
            [
                'naam' => 'FietsFix Groen E-bike', 'categorie' => 'E-bike', 'prijs_euro' => 2899.00,
                'beschrijving' => 'Een comfortabele elektrische stadsfiets met lage instap voor woon-werkverkeer en dagelijkse ritten. De middenmotor en geïntegreerde accu zorgen voor rustige, natuurlijke ondersteuning, terwijl de verende voorvork extra comfort geeft op ongelijke wegen.',
                'afbeelding' => 'images/winkel/fietsfix-groen-e-bike.png',
                'specificaties' => ['Frame' => 'Aluminium, lage instap', 'Framemaat' => '54 cm', 'Rijhouding' => 'Rechtop en comfortabel', 'Motor' => 'Middenmotor, 250 W', 'Accu' => '625 Wh, geïntegreerd', 'Actieradius' => 'Tot 120 km, afhankelijk van omstandigheden', 'Versnellingen' => '9', 'Remmen' => 'Hydraulische schijfremmen', 'Verlichting' => 'Voor- en achterlicht', 'Kleur' => 'Bosgroen'], 'voorraad' => 5,
            ],
            [
                'naam' => 'FietsFix Samen', 'categorie' => 'Bakfiets', 'prijs_euro' => 3999.00,
                'beschrijving' => 'Een elektrische bakfiets voor boodschappen, gezinsritten en grotere ladingen. De ruime voorbak biedt plaats aan spullen of passagiers, terwijl de middenmotor helpt bij optrekken en langere ritten. Dankzij de hydraulische schijfremmen rem je gecontroleerd, ook met extra gewicht. Laat je in de winkel adviseren over veilig gebruik, zitplaatsen, regenhuiven en andere passende accessoires.',
                'afbeelding' => 'images/winkel/bakfiets.png',
                'specificaties' => ['Frame' => 'Aluminium', 'Uitvoering' => 'Tweewieler met voorbak', 'Bak' => 'Ruime houten voorbak', 'Motor' => 'Middenmotor, 250 W', 'Accu' => '500 Wh, uitneembaar', 'Remmen' => 'Hydraulische schijfremmen', 'Gebruik' => 'Gezinsritten, boodschappen en bagage', 'Advies' => 'Vraag in de winkel naar veilige zitplaatsen en accessoires', 'Kleur' => 'Terracotta'], 'voorraad' => 5,
            ],
        ];

        foreach ($producten as $product) {
            // Reken pas bij het opslaan om naar centen; zo blijft invoeren leesbaar.
            $product['prijs_cent'] = (int) round($product['prijs_euro'] * 100);
            unset($product['prijs_euro']);

            $huidigProduct = Product::firstOrCreate(['naam' => $product['naam']], $product);
            $huidigProduct->update([
                'categorie' => $product['categorie'],
                'prijs_cent' => $product['prijs_cent'],
                'beschrijving' => $product['beschrijving'],
                'afbeelding' => $product['afbeelding'],
                'specificaties' => $product['specificaties'],
                'voorraad' => $product['voorraad'],
            ]);
        }

        // Voeg ook de acht merken toe wanneer je het voorbeeldassortiment installeert.
        $this->call(MerkenSeeder::class);
        $this->call(AccessoiresSeeder::class);
    }
}
