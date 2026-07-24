<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('zahlungen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vertrag_id')->constrained('vertraege')->restrictOnDelete();
            $table->integer('jahr')->comment('Abrechnungsjahr');
            $table->decimal('betrag', 10, 2);
            $table->date('faellig_am')->nullable()->comment('Fälligkeitsdatum');
            $table->date('bezahlt_am')->nullable()->comment('Eingang der Zahlung');
            $table->enum('status', ['offen', 'bezahlt', 'gemahnt', 'storniert'])->default('offen');
            $table->string('zahlungsart')->nullable()->comment('Überweisung, Bar, SEPA …');
            $table->string('referenz')->nullable()->comment('Verwendungszweck / Referenz');
            $table->text('notizen')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('zahlungen');
    }
};
