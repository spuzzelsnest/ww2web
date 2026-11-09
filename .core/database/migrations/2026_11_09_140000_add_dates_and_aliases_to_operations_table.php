<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/*
 * Operations get a period and other names:
 *   start_date / end_date  used by search (dates, sorting) and footage:check-dates
 *   aliases                other names, comma separated, found by the search
 *
 * The values below are a starting point (commonly cited dates). Operations
 * without one clear end (Occupation, Liberation of France / Belgium, Battle
 * for Germany) are a judgement call: change them in the database as you like.
 * Only rows that are still empty are filled, so running this on a database
 * where you already set your own values changes nothing.
 */
return new class extends Migration
{
    private array $periods = [
        // name => [start, end (null = open), aliases]
        'Phoney War' => ['1939-09-03', '1940-05-10', 'Drôle de guerre, Sitzkrieg'],
        'Fall Gelb' => ['1940-05-10', '1940-06-25', 'Case Yellow, Battle of France, Battle of the Netherlands, Battle of Belgium, Blitzkrieg 1940, May 1940'],
        'Occupation' => ['1940-05-10', '1945-05-08', 'Occupied'],
        'Jubilee' => ['1942-08-19', '1942-08-19', 'Dieppe Raid, Dieppe'],
        'Overlord' => ['1944-06-06', '1944-08-30', 'D-Day, Normandy landings, Neptune, Utah, Omaha, Gold, Juno, Sword'],
        'Battle for Normandy' => ['1944-06-06', '1944-08-30', 'Normandy, Normandy campaign'],
        'Cobra' => ['1944-07-25', '1944-07-31', 'Operation Cobra, Breakout, Saint-Lô breakout'],
        'Falaise Gap' => ['1944-08-12', '1944-08-21', 'Falaise Pocket, Chambois, Trun'],
        'Liberation of France' => ['1944-06-06', '1945-02-09', 'France 1944'],
        'Liberation of Belgium' => ['1944-09-02', '1945-02-04', 'Belgium 1944'],
        'Market Garden' => ['1944-09-17', '1944-09-25', 'Operation Market Garden, Arnhem, A Bridge Too Far, Nijmegen, Eindhoven'],
        'Liberation of The Netherlands' => ['1944-09-12', '1945-05-05', 'Netherlands 1944, Netherlands 1945, Holland'],
        'Battle of the Bulge' => ['1944-12-16', '1945-01-25', 'Bulge, Ardennes Offensive, Ardennenoffensive, Wacht am Rhein, Rundstedt Offensive, Ardennes 1944'],
        'Battle for Germany' => ['1944-09-12', '1945-05-08', 'Germany 1945, Siegfried Line, Westwall, Rhineland, Hürtgen Forest'],
        'Battle of Berlin' => ['1945-04-16', '1945-05-02', 'Berlin 1945, Fall of Berlin'],
        'Armistice' => ['1945-05-08', '1945-05-08', 'VE Day, Victory in Europe, Surrender'],
        'Post-War' => ['1945-05-08', null, 'After the war, Postwar'],
    ];

    public function up(): void
    {
        Schema::table('operations', function (Blueprint $table) {
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('aliases', 255)->nullable();
        });

        // Typo in the original seed data.
        DB::table('operations')->where('operation', 'Falaise Gab')->update(['operation' => 'Falaise Gap']);

        foreach ($this->periods as $name => [$start, $end, $aliases]) {
            DB::table('operations')
                ->where('operation', $name)
                ->whereNull('start_date')
                ->update(['start_date' => $start, 'end_date' => $end, 'aliases' => $aliases]);
        }
    }

    public function down(): void
    {
        Schema::table('operations', function (Blueprint $table) {
            $table->dropColumn(['start_date', 'end_date', 'aliases']);
        });
    }
};
