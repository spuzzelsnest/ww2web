<?php

namespace Tests\Feature;

use App\Models\Footage;
use App\Models\Tour;
use App\Models\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class ToursAndNowTest extends TestCase
{
    use RefreshDatabase;

    private function footage(string $name, array $extra = []): Footage
    {
        foreach (['types' => ['type' => 'Afoto', 'description' => 'Allied Photo'], 'countries' => ['country' => 'Belgium'], 'sources' => ['source' => 'replica'], 'operations' => ['operation' => 'Battle of the Bulge']] as $table => $row) {
            if (! DB::table($table)->exists()) {
                DB::table($table)->insert($row);
            }
        }

        return Footage::create(array_merge([
            'name' => $name, 'info' => 'Info '.$name, 'operationId' => 1, 'date' => '1944-12-26',
            'place' => 'Bastogne', 'countryId' => 1, 'sourceId' => 1, 'remark' => '', 'typeId' => 1,
            'lat' => 50.0, 'lng' => 5.7, 'published' => true,
        ], $extra));
    }

    public function test_the_map_page_sends_published_tours_with_units(): void
    {
        $a = $this->footage('bastogne02');
        $b = $this->footage('bastogne03');
        $unit = Unit::create(['name' => '506th PIR', 'full_name' => '506th Parachute Infantry Regiment']);

        $tour = Tour::create(['slug' => 'test', 'title' => 'Test tour', 'published' => true]);
        $tour->stops()->create(['footage_id' => $b->id, 'position' => 2]);
        $tour->stops()->create(['footage_id' => $a->id, 'position' => 1, 'unit_id' => $unit->id]);

        Tour::create(['slug' => 'draft', 'title' => 'Draft tour', 'published' => false]);

        $tours = $this->get('/')->assertOk()->viewData('tours');

        $this->assertCount(1, $tours);
        $this->assertSame('Test tour', $tours[0]['title']);
        $this->assertSame([$a->id, $b->id], $tours[0]['stops']->pluck('footageId')->all());
        $this->assertSame('506th PIR', $tours[0]['stops'][0]['unit']);
    }

    public function test_link_now_links_name_n_pictures_and_unlinks_missing_ones(): void
    {
        $dir = sys_get_temp_dir().'/ww2web-now-'.uniqid();
        File::ensureDirectoryExists($dir.'/images');
        $this->app->usePublicPath($dir);

        $with = $this->footage('aachen01');
        $gone = $this->footage('aachen02', ['now_name' => 'aachen02_n']);
        $cased = $this->footage('stMarie03');
        touch($dir.'/images/aachen01_n.jpg');
        touch($dir.'/images/StMarie03_n.jpg');

        $this->artisan('footage:link-now')->assertSuccessful();

        $this->assertSame('aachen01_n', $with->fresh()->now_name);
        $this->assertNull($gone->fresh()->now_name);
        $this->assertSame('StMarie03_n', $cased->fresh()->now_name);   // file's own spelling

        File::deleteDirectory($dir);
    }

    public function test_the_example_tour_seeder_skips_missing_footage(): void
    {
        DB::table('operations')->insert(['operation' => 'Battle of the Bulge']);
        $this->footage('bastogne10');

        $this->seed(\Database\Seeders\ExampleTourSeeder::class);
        $this->seed(\Database\Seeders\ExampleTourSeeder::class);   // twice: no duplicates

        $this->assertSame(1, Tour::count());
        $this->assertSame(1, Tour::first()->stops()->count());
        $this->assertSame(4, Unit::count());
    }
}
