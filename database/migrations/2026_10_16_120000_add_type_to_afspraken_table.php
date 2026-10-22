<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('afspraken', function (Blueprint $table) {
            $table->string('type', 30)->default('onderhoud')->after('tijd');
        });
    }

    public function down(): void
    {
        Schema::table('afspraken', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
