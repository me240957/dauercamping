<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paechter', function (Blueprint $table) {
            $table->id();
            $table->string('vorname');
            $table->string('nachname');
            $table->string('email')->nullable()->unique();
            $table->string('telefon')->nullable();
            $table->string('mobil')->nullable();
            $table->string('strasse')->nullable();
            $table->string('plz', 10)->nullable();
            $table->string('ort')->nullable();
            $table->date('geburtsdatum')->nullable();
            $table->enum('status', ['aktiv', 'inaktiv'])->default('aktiv');
            $table->text('notizen')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paechter');
    }
};
