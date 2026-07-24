<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stellplaetze', function (Blueprint $table) {
            $table->id();
            $table->string('nummer', 20)->unique()->comment('Stellplatznummer, z.B. A-01');
            $table->string('bezeichnung')->nullable()->comment('Optionaler Name/Beschreibung');
            $table->decimal('groesse_qm', 8, 2)->nullable()->comment('Größe in m²');
            $table->string('lage')->nullable()->comment('Bereich/Lage im Campingplatz');
            $table->enum('status', ['aktiv', 'inaktiv', 'gesperrt'])->default('aktiv');
            $table->text('notizen')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stellplaetze');
    }
};
