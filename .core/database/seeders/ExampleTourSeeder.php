<?php

namespace Database\Seeders;

use App\Models\Footage;
use App\Models\Tour;
use App\Models\Unit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * One example tour that links several units: the defence and relief of
 * Bastogne. Safe to run again; it updates the tour instead of adding a copy.
 *
 *   php artisan db:seed --class=ExampleTourSeeder
 */
class ExampleTourSeeder extends Seeder
{
    public function run(): void
    {
        $units = [
            '28th Infantry Division' => ['full_name' => '28th Infantry Division', 'side' => 'allied'],
            '506th PIR' => ['full_name' => '506th Parachute Infantry Regiment, 101st Airborne Division', 'side' => 'allied'],
            '101st Airborne Division' => ['full_name' => '101st Airborne Division "Screaming Eagles"', 'side' => 'allied'],
            'Third US Army' => ['full_name' => 'Third United States Army (General Patton)', 'side' => 'allied'],
        ];

        foreach ($units as $name => $values) {
            Unit::updateOrCreate(['name' => $name], $values);
        }

        // [footage name, typeId, unit, title override]
        $stops = [
            ['bastogne10', 1, '28th Infantry Division', null],
            ['bastogne11', 1, '506th PIR', null],
            ['bastogne01', 1, '101st Airborne Division', 'Bastogne - the supply drop'],
            ['bastogne06', 1, '101st Airborne Division', null],
            ['bastogne03', 1, 'Third US Army', null],
            ['bastogne07', 1, 'Third US Army', null],
            ['foy01', 1, '101st Airborne Division', null],
            ['noville03', 1, null, null],
        ];

        DB::transaction(function () use ($stops) {
            $tour = Tour::updateOrCreate(['slug' => 'holding-bastogne'], [
                'title' => 'Holding Bastogne',
                'intro' => 'From the first days of the German offensive to the relief of the town: how the 28th Infantry, the 101st Airborne and Patton\'s Third Army held and relieved Bastogne, December 1944 to January 1945.',
                'operationId' => DB::table('operations')->where('operation', 'Battle of the Bulge')->value('id'),
                'published' => true,
                'sort' => 1,
            ]);

            $tour->stops()->delete();

            $position = 1;
            foreach ($stops as [$name, $typeId, $unit, $title]) {
                $footageId = Footage::where('name', $name)->where('typeId', $typeId)->value('id');

                if ($footageId === null) {
                    $this->command?->warn("Skipped stop: no footage named {$name}");
                    continue;
                }

                $tour->stops()->create([
                    'footage_id' => $footageId,
                    'unit_id' => $unit ? Unit::where('name', $unit)->value('id') : null,
                    'position' => $position++,
                    'title' => $title,
                ]);
            }
        });
    }
}
