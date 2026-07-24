<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vertraege', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stellplatz_id')->constrained('stellplaetze')->restrictOnDelete();
            $table->foreignId('paechter_id')->constrained('paechter')->restrictOnDelete();
            $table->date('beginn');
            $table->date('ende')->nullable()->comment('null = unbefristet');
            $table->decimal('jahresbetrag', 10, 2)->default(0);
            $table->enum('zahlungsrhythmus', ['jaehrlich', 'halbjaehrlich', 'vierteljaehrlich', 'monatlich'])->default('jaehrlich');
            $table->enum('status', ['aktiv', 'gekuendigt', 'beendet'])->default('aktiv');
            $table->date('kuendigungsdatum')->nullable();
            $table->text('notizen')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vertraege');
    }
};
