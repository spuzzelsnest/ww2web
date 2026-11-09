<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * "Then & Now": the same spot photographed today.
 *
 * The picture lives next to the original with "_n" added to the name:
 *   images/aachen01.jpg    the original (then)
 *   images/aachen01_n.jpg  the same spot today (now)
 *
 * now_name is filled by "php artisan footage:link-now" for every footage
 * whose <name>_n.jpg exists, so the page never links a missing file.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('footages', function (Blueprint $table) {
            $table->string('now_name', 64)->nullable()->after('name');
            $table->date('now_taken_on')->nullable()->after('now_name');
            $table->string('now_credit', 120)->nullable()->after('now_taken_on');
        });
    }

    public function down(): void
    {
        Schema::table('footages', function (Blueprint $table) {
            $table->dropColumn(['now_name', 'now_taken_on', 'now_credit']);
        });
    }
};
