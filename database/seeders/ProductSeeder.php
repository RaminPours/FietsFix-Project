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
                'beschrijving' => 'Een comfortabele stadsfiets voor dagelijkse ritten naar school, werk of de winkel. De rechte zithouding en lage instap maken op- en afstappen gemakkelijk.',
                'afbeelding' => 'images/winkel/stadsfiets.svg',
                'specificaties' => ['Frame' => 'Aluminium, lage instap', 'Framemaat' => '53 cm', 'Versnellingen' => '7', 'Remmen' => 'Rollerbrakes', 'Kleur' => 'Zeegroen', 'Gebruik' => 'Dagelijkse ritten'],
            ],
            [
                'naam' => 'FietsFix Elektrisch', 'categorie' => 'E-bike', 'prijs_cent' => 249900,
                'beschrijving' => 'Een elektrische fiets met ondersteuning tijdens het trappen. Geschikt voor woon-werkverkeer en ontspannen tochten. De werkelijke actieradius hangt af van onder andere ondersteuning, belading en weer.',
                'afbeelding' => 'images/winkel/e-bike.svg',
                'specificaties' => ['Frame' => 'Aluminium, lage instap', 'Framemaat' => '53 cm', 'Motor' => 'Middenmotor, 250 W', 'Accu' => '500 Wh, uitneembaar', 'Versnellingen' => '8', 'Remmen' => 'Hydraulische schijfremmen', 'Kleur' => 'Donkerblauw'],
            ],
            [
                'naam' => 'FietsFix Samen', 'categorie' => 'Bakfiets', 'prijs_cent' => 399900,
                'beschrijving' => 'Een elektrische bakfiets voor boodschappen en gezinsritten. De ruime bak en elektrische ondersteuning helpen je extra bagage mee te nemen. Laat je in de winkel adviseren over veilig gebruik en geschikte accessoires.',
                'afbeelding' => 'images/winkel/bakfiets.svg',
                'specificaties' => ['Frame' => 'Aluminium', 'Uitvoering' => 'Tweewieler met voorbak', 'Motor' => 'Middenmotor, 250 W', 'Accu' => '500 Wh', 'Remmen' => 'Hydraulische schijfremmen', 'Kleur' => 'Terracotta'],
            ],
        ];

        foreach ($producten as $product) {
            // Bestaande producten worden bij opnieuw uitvoeren niet overschreven.
            Product::firstOrCreate(['naam' => $product['naam']], $product);
        }

        // Voeg ook de acht merken toe wanneer je het voorbeeldassortiment installeert.
        $this->call(MerkenSeeder::class);
        $this->call(AccessoiresSeeder::class);
    }
}
