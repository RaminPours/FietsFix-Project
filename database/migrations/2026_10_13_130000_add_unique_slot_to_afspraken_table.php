<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('afspraken', function (Blueprint $table) {
            $table->unique(['vestiging', 'datum', 'tijd'], 'afspraken_vestiging_datum_tijd_unique');
        });
    }

    public function down(): void
    {
        Schema::table('afspraken', function (Blueprint $table) {
            $table->dropUnique('afspraken_vestiging_datum_tijd_unique');
        });
    }
};
