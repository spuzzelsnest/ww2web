<?php

namespace Tests\Feature;

use App\Models\Footage;
use App\Models\Operation;
use App\Models\Tour;
use App\Models\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SearchDataTest extends TestCase
{
    use RefreshDatabase;

    private function footage(string $name, string $date, int $operationId): Footage
    {
        foreach (['types' => ['type' => 'Afoto', 'description' => 'Allied Photo'], 'countries' => ['country' => 'Belgium'], 'sources' => ['source' => 'replica']] as $table => $row) {
            if (! DB::table($table)->exists()) {
                DB::table($table)->insert($row);
            }
        }

        return Footage::create([
            'name' => $name, 'info' => 'Info', 'operationId' => $operationId, 'date' => $date,
            'place' => 'Bastogne', 'countryId' => 1, 'sourceId' => 1, 'remark' => '', 'typeId' => 1,
            'lat' => 50.0, 'lng' => 5.7, 'published' => true,
        ]);
    }

    public function test_the_migration_fills_known_operations(): void
    {
        // The tables are migrated before any row exists, so run the fill again on fresh rows.
        DB::table('operations')->insert([['operation' => 'Battle of the Bulge'], ['operation' => 'Falaise Gab']]);
        $migration = require database_path('migrations/2026_11_09_140000_add_dates_and_aliases_to_operations_table.php');
        $migration->down();
        $migration->up();

        $bulge = Operation::where('operation', 'Battle of the Bulge')->first();
        $this->assertSame('1944-12-16', $bulge->start_date->format('Y-m-d'));
        $this->assertSame('1945-01-25', $bulge->end_date->format('Y-m-d'));
        $this->assertContains('Ardennes Offensive', $bulge->aliasList());
        $this->assertTrue(Operation::where('operation', 'Falaise Gap')->exists());
    }

    public function test_the_map_page_sends_operation_aliases_type_and_units(): void
    {
        $op = Operation::create(['operation' => 'Battle of the Bulge', 'aliases' => 'Bulge, Ardennes Offensive']);
        $footage = $this->footage('bastogne11', '1944-12-19', $op->id);

        $unit = Unit::create(['name' => '506th PIR', 'full_name' => '506th Parachute Infantry Regiment']);
        $tour = Tour::create(['slug' => 't', 'title' => 'T', 'published' => true]);
        $tour->stops()->create(['footage_id' => $footage->id, 'position' => 1, 'unit_id' => $unit->id]);

        $item = $this->get('/')->assertOk()->viewData('footages')->firstWhere('id', $footage->id);

        $this->assertSame('Battle of the Bulge', $item->operation);
        $this->assertSame('Bulge, Ardennes Offensive', $item->operation_aliases);
        $this->assertSame('Allied Photo', $item->type_name);
        $this->assertSame(['506th PIR', '506th Parachute Infantry Regiment'], $item->units);
    }

    public function test_check_dates_reports_missing_impossible_and_outside(): void
    {
        $op = Operation::create(['operation' => 'Battle of the Bulge', 'start_date' => '1944-12-16', 'end_date' => '1945-01-25']);
        $this->footage('ok01', '1944-12-20', $op->id);
        $this->footage('partial01', '1944-12-00', $op->id);       // December 1944 overlaps: fine
        $this->footage('nodate01', '0000-00-00', $op->id);
        $this->footage('typo01', '1044-06-07', $op->id);
        $this->footage('late01', '1945-12-26', $op->id);

        $this->artisan('footage:check-dates')
            ->expectsOutputToContain('nodate01')
            ->expectsOutputToContain('typo01')
            ->expectsOutputToContain('late01')
            ->doesntExpectOutputToContain('ok01')
            ->doesntExpectOutputToContain('partial01')
            ->assertSuccessful();
    }
}
