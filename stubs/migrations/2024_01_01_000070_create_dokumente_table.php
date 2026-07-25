<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokumente', function (Blueprint $table) {
            $table->id();
            $table->string('titel');
            $table->enum('kategorie', ['angebot', 'rechnung', 'zahlung', 'sonstiges'])->default('sonstiges');
            $table->string('dateiname');          // Originalname
            $table->string('dateipfad');          // Pfad in storage
            $table->string('dateityp', 100);      // MIME-Type
            $table->unsignedBigInteger('dateigroesse')->default(0); // Bytes
            $table->text('beschreibung')->nullable();
            $table->date('dokument_datum')->nullable()->comment('Datum des Dokuments (z.B. Rechnungsdatum)');
            // Optionale Zuordnungen
            $table->foreignId('paechter_id')->nullable()->constrained('paechter')->nullOnDelete();
            $table->foreignId('vertrag_id')->nullable()->constrained('vertraege')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index('kategorie');
            $table->index('paechter_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumente');
    }
};
