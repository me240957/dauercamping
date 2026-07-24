<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('uebernachtungen', function (Blueprint $table) {
            $table->date('abreisedatum')->nullable()->after('datum');
        });
    }

    public function down(): void
    {
        Schema::table('uebernachtungen', function (Blueprint $table) {
            $table->dropColumn('abreisedatum');
        });
    }
};
