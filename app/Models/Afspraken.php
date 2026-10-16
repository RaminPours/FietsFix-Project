<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Afspraken extends Model
{
    public static function tijdstippen(): array
    {
        return array_map(
            fn (int $minuten) => sprintf('%02d:%02d', intdiv($minuten, 60), $minuten % 60),
            range(8 * 60, 17 * 60, 15)
        );
    }

    protected $table = 'afspraken';
    protected $fillable = [
        'naam',
        'user_id',
        'email',
        'telefoonnummer',
        'vestiging',
        'fietstype',
        'fietsmerk',
        'probleem',
        'datum',
        'tijd',
        'type',
    ];
}
