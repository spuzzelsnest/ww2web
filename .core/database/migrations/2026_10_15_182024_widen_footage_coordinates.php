<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/*
 * The original migration used double('lat', 10, 8) and double('lng', 10, 8).
 * A database created under Laravel 9 therefore has DOUBLE(10,8) columns: only
 * 2 digits before the dot, so any longitude of 100 or more (Japan, Pacific,
 * the Far East) cannot be stored. Laravel 11+ ignores the precision arguments,
 * so a database freshly migrated on Laravel 12 already has plain DOUBLE.
 * This migration makes both cases identical and is harmless on plain DOUBLE.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('footages', function (Blueprint $table) {
            $table->double('lat')->change();
            $table->double('lng')->change();
        });
    }

    public function down(): void
    {
        // MySQL only; the schema builder can no longer express DOUBLE(10,8).
        DB::statement('ALTER TABLE footages MODIFY lat DOUBLE(10,8) NOT NULL');
        DB::statement('ALTER TABLE footages MODIFY lng DOUBLE(10,8) NOT NULL');
    }
};
