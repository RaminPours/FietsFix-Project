<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class AccessoiresSeeder extends Seeder
{
    public function run(): void
    {
        // Voorbeeldartikelen: vul voor echte verkoop de gegevens van je leverancier in.
        $artikelen = [
            ['LED verlichtingsset', 'Verlichting', 2495, 'verlichting',
                'Een voor- en achterlicht voor extra zichtbaarheid. Deze voorbeeldset wordt los op de fiets gemonteerd.',
                ['Inhoud' => 'Voorlicht en achterlicht', 'Voeding' => 'Batterijen', 'Montage' => 'Controleer de diameter van stuur en zadelpen']],
            ['USB achterlicht', 'Verlichting', 1495, 'achterlicht',
                'Een los achterlicht met oplaadbare accu. Handig als vervanging of aanvulling op je verlichting.',
                ['Kleur licht' => 'Rood', 'Voeding' => 'Oplaadbaar via USB', 'Montage' => 'Controleer de bevestiging aan je zadelpen']],
            ['Kettingslot 90 cm', 'Sloten', 3495, 'slot',
                'Een kettingslot met beschermhoes om je fiets aan een vast object vast te zetten.',
                ['Lengte' => '90 cm', 'Sluiting' => 'Sleutel', 'Keurmerk' => 'Geen keurmerk opgegeven voor dit voorbeeldartikel']],
            ['Buitenband 28 inch', 'Banden', 2295, 'band',
                'Een voorbeeldband voor dagelijks gebruik. Vergelijk de ETRTO-maat met de maat op je huidige band voordat je bestelt.',
                ['ETRTO-maat' => '37-622', 'Type' => 'Buitenband', 'Montage' => 'Controleer velgmaat en beschikbare ruimte']],
            ['Binnenband 28 inch', 'Banden', 895, 'binnenband',
                'Een reservebinnenband voor onderweg of voor het vervangen van een lekke band.',
                ['Bandmaat' => '28/47-622', 'Ventiel' => 'Dunlop, 40 mm', 'Montage' => 'Controleer bandmaat en ventielgat']],
            ['Dubbele fietstas 30 liter', 'Fietstassen', 4495, 'fietstas',
                'Twee tassen voor boodschappen of spullen voor je werk. Controleer of de bevestiging bij je bagagedrager past.',
                ['Inhoud' => '30 liter totaal', 'Bevestiging' => 'Riemen aan de bagagedrager', 'Kleur' => 'Donkergroen']],
            ['Comfortzadel', 'Zadels', 2995, 'zadel',
                'Een breed zadel voor een rechte zithouding. De juiste zadelbreedte en montage zijn afhankelijk van je fiets en houding.',
                ['Gebruik' => 'Stadsfiets, rechte zithouding', 'Bevestiging' => 'Zadelrails', 'Montage' => 'Controleer de zadelklem']],
            ['Platformpedalen set', 'Pedalen', 1995, 'pedalen',
                'Een paar platformpedalen met reflectoren en een gripprofiel voor dagelijks gebruik.',
                ['Inhoud' => 'Linker- en rechterpedaal', 'Schroefdraad' => '9/16 inch', 'Montage' => 'Controleer de aansluiting op je crank']],
        ];

        foreach ($artikelen as [$naam, $categorie, $prijs, $afbeelding, $beschrijving, $specificaties]) {
            // Opnieuw uitvoeren voegt geen dubbele artikelen toe en bewaart bestaande wijzigingen.
            Product::firstOrCreate(['naam' => $naam], [
                'categorie' => $categorie,
                'prijs_cent' => $prijs,
                'afbeelding' => 'images/accessoires/'.$afbeelding.'.png',
                'beschrijving' => $beschrijving,
                'specificaties' => $specificaties,
            ]);
        }
    }
}
