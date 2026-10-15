<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bestelling extends Model
{
    protected $table = 'bestellingen';

    protected $fillable = [
        'user_id', 'product_id', 'product_naam', 'prijs_cent', 'aantal',
        'naam', 'email', 'telefoonnummer', 'vestiging', 'status', 'formuliernummer', 'bestelnummer',
    ];
}
