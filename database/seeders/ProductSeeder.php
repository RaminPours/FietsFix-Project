<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Voorbeeldproducten: vervang prijzen en specificaties door je eigen assortiment.
        $producten = [
            [
                'naam' => 'FietsFix Stad', 'categorie' => 'Stadsfiets', 'prijs_cent' => 69900,
                'beschrijving' => 'Een comfortabele stadsfiets voor dagelijkse ritten naar school, werk of de winkel. De lage instap maakt op- en afstappen gemakkelijk en de rechte zithouding geeft goed overzicht in het verkeer. Met zeven versnellingen kies je prettig een tempo op vlakke wegen en bij lichte tegenwind. Spatborden, verlichting en een bagagedrager maken deze fiets praktisch voor dagelijks gebruik.',
                'afbeelding' => 'images/winkel/stadsfiets.png',
                'specificaties' => ['Frame' => 'Aluminium, lage instap', 'Framemaat' => '53 cm', 'Rijhouding' => 'Rechtop en comfortabel', 'Versnellingen' => '7', 'Remmen' => 'Rollerbrakes', 'Verlichting' => 'Voor- en achterlicht', 'Bagagedrager' => 'Achterdrager voor tas of fietstas', 'Kleur' => 'Zeegroen', 'Gebruik' => 'Dagelijkse ritten in de stad'],
            ],
            [
                'naam' => 'FietsFix Elektrisch', 'categorie' => 'E-bike', 'prijs_cent' => 249900,
                'beschrijving' => 'Een elektrische fiets met trapondersteuning voor woon-werkverkeer en ontspannen tochten. De middenmotor ondersteunt natuurlijk tijdens het optrekken en op hellingen, terwijl de uitneembare accu thuis of op het werk kan worden opgeladen. De lage instap en comfortabele zithouding maken de fiets geschikt voor dagelijks gebruik. De werkelijke actieradius hangt af van de gekozen ondersteuning, belading, bandenspanning, route en het weer.',
                'afbeelding' => 'images/winkel/e-bike.png',
                'specificaties' => ['Frame' => 'Aluminium, lage instap', 'Framemaat' => '53 cm', 'Rijhouding' => 'Comfortabel en rechtop', 'Motor' => 'Middenmotor, 250 W', 'Accu' => '500 Wh, uitneembaar', 'Opladen' => 'Thuis of op het werk met meegeleverde lader', 'Versnellingen' => '8', 'Remmen' => 'Hydraulische schijfremmen', 'Gebruik' => 'Woon-werkverkeer en recreatieve tochten', 'Kleur' => 'Donkerblauw'],
            ],
            [
                'naam' => 'FietsFix Samen', 'categorie' => 'Bakfiets', 'prijs_cent' => 399900,
                'beschrijving' => 'Een elektrische bakfiets voor boodschappen, gezinsritten en grotere ladingen. De ruime voorbak biedt plaats aan spullen of passagiers, terwijl de middenmotor helpt bij optrekken en langere ritten. Dankzij de hydraulische schijfremmen rem je gecontroleerd, ook met extra gewicht. Laat je in de winkel adviseren over veilig gebruik, zitplaatsen, regenhuiven en andere passende accessoires.',
                'afbeelding' => 'images/winkel/bakfiets.png',
                'specificaties' => ['Frame' => 'Aluminium', 'Uitvoering' => 'Tweewieler met voorbak', 'Bak' => 'Ruime houten voorbak', 'Motor' => 'Middenmotor, 250 W', 'Accu' => '500 Wh, uitneembaar', 'Remmen' => 'Hydraulische schijfremmen', 'Gebruik' => 'Gezinsritten, boodschappen en bagage', 'Advies' => 'Vraag in de winkel naar veilige zitplaatsen en accessoires', 'Kleur' => 'Terracotta'],
            ],
        ];

        foreach ($producten as $product) {
            $huidigProduct = Product::firstOrCreate(['naam' => $product['naam']], $product);
            $huidigProduct->update([
                'beschrijving' => $product['beschrijving'],
                'afbeelding' => $product['afbeelding'],
                'specificaties' => $product['specificaties'],
            ]);
        }

        // Voeg ook de acht merken toe wanneer je het voorbeeldassortiment installeert.
        $this->call(MerkenSeeder::class);
        $this->call(AccessoiresSeeder::class);
    }
}
