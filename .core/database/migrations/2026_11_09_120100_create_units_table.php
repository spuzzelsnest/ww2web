<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * Military units (companies, battalions, divisions, Kampfgruppen) that
 * tour stops can point to, so one tour can link several units together.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('name', 80);                    // short: "506th PIR"
            $table->string('full_name', 200)->nullable();  // "506th Parachute Infantry Regiment, 101st Airborne Division"
            $table->string('side', 10)->nullable();        // "allied" or "axis"
            $table->timestamps();

            $table->unique('name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
