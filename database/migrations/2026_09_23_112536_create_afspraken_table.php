<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('afspraken', function (Blueprint $table) {
            $table->id();
            $table->string('naam', 250);
            $table->string('email', 250);
            $table->string('telefoonnummer', 250);
            $table->string('fietstype', 250);
            $table->string('fietsmerk', 250);
            $table->string('probleem', 500);
            $table->date('datum');
            $table->time('tijd');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('afspraken');
    }
};
