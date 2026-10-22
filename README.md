# FietsFix

FietsFix is een webapplicatie voor een fietsenwinkel. Klanten kunnen fietsen en
accessoires bekijken, een proefrit of onderhoudsafspraak aanvragen en producten
bestellen om in de winkel af te halen. Medewerkers beheren vanuit een afgeschermd
dashboard de afspraken, bestellingen en voorraad.

De applicatie brengt de belangrijkste zaken van de winkel samen op één plek: van
het eerste bezoek aan de webshop tot het plannen van werkplaatsafspraken en het
verwerken van bestellingen.

## Wat kun je ermee?

- **Fietsen en accessoires bekijken** — blader door het assortiment, filter op
  categorie of merk en bekijk productinformatie en specificaties.
- **Een winkelmandje en bestelling maken** — verzamel artikelen in een mandje en
  plaats na inloggen een bestelling. De bestelling is bedoeld voor afhalen in de
  winkel; online betalen is niet ingericht.
- **Afspraken aanvragen** — plan een onderhoudsbeurt of vraag een proefrit aan.
  Klanten kunnen hun afspraken terugzien in hun account.
- **Bestellingen volgen** — klanten bekijken hun bestellingen en kunnen een
  bestelling annuleren zolang dat is toegestaan.
- **Winkelwerk beheren** — medewerkers bekijken de planning, werken
  bestelstatussen bij en beheren de voorraad via het personeelsdashboard.
- **Accounts gebruiken** — registratie en inloggen geven toegang tot persoonlijke
  bestellingen, afspraken en profielinstellingen.

## Gebouwd met

FietsFix is gemaakt met **Laravel 12**, **PHP 8.2+**, Blade, Vite en MYSQL als
standaard lokale database. Laravel verzorgt onder meer de routes, database,
validatie en accounts. De pagina's zijn opgebouwd met Blade-templates.

## Lokaal starten

Benodigd: PHP 8.2 of hoger, Composer, Node.js en npm.

```sh
composer run setup
php artisan db:seed --class=ProductSeeder
composer run dev
```

De setup installeert de PHP- en JavaScript-afhankelijkheden, maakt een lokale
`.env` aan, genereert een applicatiesleutel, voert migraties uit en bouwt de
frontend. De seeder voegt voorbeeldfietsen, accessoires en merken toe. Met
`composer run dev` start je de Laravel-server en Vite voor lokale ontwikkeling.

## Technische documentatie

Meer informatie over de fietswinkel, het assortiment en bestelproces staat in
[`docs/fietswinkel.md`](docs/fietswinkel.md).
