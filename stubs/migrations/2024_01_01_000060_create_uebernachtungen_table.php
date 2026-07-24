<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('uebernachtungen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vertrag_id')->constrained('vertraege')->restrictOnDelete();
            $table->date('datum')->comment('Anreisedatum / erster Übernachtungstag');
            $table->unsignedSmallInteger('anzahl_naechte')->default(1);
            $table->unsignedSmallInteger('anzahl_personen')->default(1);
            $table->text('notizen')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['vertrag_id', 'datum']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('uebernachtungen');
    }
};
