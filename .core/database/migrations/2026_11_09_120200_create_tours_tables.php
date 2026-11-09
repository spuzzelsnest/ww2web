<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * Guided tours: an ordered list of footage ("stops"). Each stop can name
 * the unit seen there and override the title or text of the footage.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tours', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 80)->unique();
            $table->string('title', 120);
            $table->text('intro')->nullable();
            $table->unsignedInteger('operationId')->nullable();   // same naming as footages
            $table->boolean('published')->default(false);
            $table->unsignedSmallInteger('sort')->default(0);
            $table->timestamps();
        });

        Schema::create('tour_stops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_id')->constrained()->cascadeOnDelete();
            // footages.id is an unsigned INT; no foreign key so this also
            // works when the old footages table is not InnoDB.
            $table->unsignedInteger('footage_id')->index();
            $table->foreignId('unit_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedSmallInteger('position');
            $table->string('title', 120)->nullable();   // empty: "<place> - <date>" of the footage
            $table->text('text')->nullable();           // empty: the footage's own info text
            $table->timestamps();

            $table->unique(['tour_id', 'position']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tour_stops');
        Schema::dropIfExists('tours');
    }
};
