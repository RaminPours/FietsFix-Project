<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'producten';

    protected $fillable = ['naam', 'merk', 'categorie', 'beschrijving', 'prijs_cent', 'afbeelding', 'specificaties', 'actief', 'voorraad'];

    protected function casts(): array
    {
        // Laravel zet de opgeslagen specificaties om naar een gewone PHP-array.
        return ['specificaties' => 'array', 'actief' => 'boolean', 'prijs_cent' => 'integer', 'voorraad' => 'integer'];
    }

    public function foto(): ?array
    {
        // Geef de modelnaam en bron van deze foto terug, of null bij een eigen afbeelding.
        $fotos = config('productfotos', []);

        return $fotos[$this->afbeelding] ?? null;
    }
}
