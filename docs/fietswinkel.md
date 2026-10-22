# Fietswinkel

Open `/winkel` of klik op **Fietswinkel** in de navigatie. Een bezoeker kan fietsen
bekijken en filteren. Een ingelogde klant kan bestellen en zijn eigen bestellingen
bekijken via **Mijn bestellingen**.

## Accessoires en onderdelen

In de algemene navigatiebalk staat **FietsFix Webshop** als uitklapmenu, met de groepen
**Fietsen** (elektrische fietsen, stadsfietsen en bakfietsen) en **Accessoires**.
Elke link opent de shop met het bijbehorende categoriefilter. Het menu opent met CSS
bij hover en sluit wanneer de muis weggaat. Met het toetsenbord opent het via Tab;
op een touchscreen via een tik. Er is geen JavaScript nodig.
`/accessoires` toont de categorieën Verlichting, Sloten, Banden, Fietstassen,
Zadels en Pedalen. De categorieën staan in `config/winkel.php`.

`AccessoiresSeeder` voegt acht voorbeeldartikelen toe aan de bestaande producttabel.
De illustraties staan in `public/images/accessoires`. Prijzen en specificaties zijn
voorbeeldgegevens. Fietsen en accessoires kunnen samen worden besteld met het bestaande
mandje; het maximum blijft 5 stuks per product. De fietsenpagina toont alleen fietsen.

Op een bestaande installatie voeg je de artikelen toe met:

```sh
php artisan db:seed --class=AccessoiresSeeder
```

Er is hiervoor geen nieuwe databasetabel of migratie nodig.

Na **Bestellen** komt de fiets eerst in het winkelmandje. Bezoekers kunnen meerdere
fietsen toevoegen, aantallen wijzigen (maximaal 5 per product) en fietsen verwijderen.
De navigatie toont het totale aantal fietsen. Inloggen is pas nodig bij het afronden.
Het mandje wordt in de sessie bewaard; het is niet gekoppeld aan een account of ander apparaat.

`WinkelmandController` beheert dit mandje. `AfrekenenController` controleert opnieuw
de prijzen en beschikbaarheid en slaat alle regels binnen één databasetransactie op.
De regels delen een `bestelnummer`. Pas na succes wordt het mandje geleegd.
Opnieuw versturen van hetzelfde formulier maakt geen dubbele bestellingen.

## Hoe de code werkt

- `WinkelController` haalt actieve producten op en toont de overzichts- en detailpagina.
- `BestellingController` controleert het formulier en slaat een bestelling op.
- `Product` gebruikt de tabel `producten`. Specificaties worden als een PHP-array gebruikt.
- `Bestelling` gebruikt de tabel `bestellingen`. De naam en prijs van de fiets worden
  bij het bestellen gekopieerd. Latere productwijzigingen veranderen eerdere bestellingen niet.
- De Blade-bestanden staan in `resources/views/winkel` en `resources/views/bestellingen`.
- De winkel werkt met normale formulieren en Laravel/PHP, zonder eigen JavaScript.

Prijzen worden opgeslagen in centen: `69900` betekent EUR 699,00. Zo rekenen we
met gehele getallen. De totaalprijs is de prijs per fiets maal het aantal.

## Voorbeeldproducten

`database/seeders/ProductSeeder.php` bevat drie fictieve modellen met voorbeeldprijzen
en specificaties. De SVG-illustraties staan in `public/images/winkel`.
Deze seeder voert ook `MerkenSeeder` uit. Die voegt een voorbeeldartikel toe voor
Batavus, Giant, Gazelle, Sparta, Stella, Cortina, Trek en Cube. De merkenlijst staat
in `config/winkel.php`; het merkfilter is te combineren met het categoriefilter.
De acht merkartikelen gebruiken echte merkfoto's. De lokale bestanden, afgebeelde
modellen, bronpagina's en oorspronkelijke downloadlinks staan in `config/productfotos.php`.
Op overzichts- en detailpagina's staat de bron bij de foto. De foto's zijn referenties:
de voorbeeldprijzen en specificaties zijn geen gegevens van de afgebeelde modellen.
De afbeeldingen zijn onbewerkt gedownload; de rechten blijven bij de oorspronkelijke
rechthebbenden. De bronvermelding is geen licentie of toestemming voor commercieel gebruik.

`php artisan db:seed --class=MerkenSeeder` vervangt de oorspronkelijke SVG-illustraties
bij bestaande merkartikelen door de foto's. Zelf ingestelde foto's worden behouden.
Vervang deze gegevens door het werkelijke assortiment voordat je echte verkopen aanbiedt.
Opnieuw seeden voegt alleen ontbrekende productnamen toe; bestaande producten worden
niet bijgewerkt. Bestaande productgegevens kun je in de tabel `producten` wijzigen.
Een product met `actief = 0` is niet zichtbaar en kan niet worden besteld.

## Installeren op een andere lokale omgeving

```sh
php artisan migrate
php artisan db:seed --class=ProductSeeder
npm run build
```

## Bestelwijze

De klant kiest 1 tot 5 fietsen en een afhaalvestiging. Er vindt geen online betaling
plaats. De bestelling krijgt de status `Ontvangen`. De winkel moet beschikbaarheid
en het afhaalmoment handmatig bevestigen via de opgegeven contactgegevens.
Er is nog geen beheerpagina, voorraadbeheer of automatische e-mailbevestiging.

## Testen

```sh
php artisan test --compact
```

De winkeltests controleren filters, productpagina's, toegang voor ingelogde klanten,
validatie, de prijs uit de database, dubbel versturen en privacy van bestellingen.
