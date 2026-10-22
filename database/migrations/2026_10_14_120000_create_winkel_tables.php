<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // De catalogus bevat de informatie die klanten over een fiets kunnen bekijken.
        Schema::create('producten', function (Blueprint $table) {
            $table->id();
            $table->string('naam');
            $table->string('categorie');
            $table->text('beschrijving');
            $table->unsignedInteger('prijs_cent');
            $table->string('afbeelding');
            $table->json('specificaties');
            $table->boolean('actief')->default(true);
            $table->timestamps();
        });

        Schema::create('bestellingen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('producten')->nullOnDelete();
            // Bewaar de naam en prijs bij aankoop, ook als het product later verandert.
            $table->string('product_naam');
            $table->unsignedInteger('prijs_cent');
            $table->unsignedInteger('aantal');
            $table->string('naam');
            $table->string('email');
            $table->string('telefoonnummer', 20);
            $table->string('vestiging');
            $table->string('status')->default('Ontvangen');
            // Een uniek formuliernummer voorkomt dubbel bestellen bij opnieuw versturen.
            $table->uuid('formuliernummer')->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bestellingen');
        Schema::dropIfExists('producten');
    }
};
