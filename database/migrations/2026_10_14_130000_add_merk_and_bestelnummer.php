<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('producten', function (Blueprint $table) {
            $table->string('merk')->nullable()->index();
        });
        Schema::table('bestellingen', function (Blueprint $table) {
            // Meerdere fietsen uit hetzelfde mandje krijgen hetzelfde bestelnummer.
            $table->uuid('bestelnummer')->nullable()->index();
        });
    }

    public function down(): void
    {
        Schema::table('bestellingen', function (Blueprint $table) {
            $table->dropColumn('bestelnummer');
        });
        Schema::table('producten', function (Blueprint $table) {
            $table->dropColumn('merk');
        });
    }
};
