<?php

namespace App\Http\Controllers;

use App\Models\Footage;
use App\Models\Tour;
use App\Support\Thumbnails;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(Thumbnails $thumbs)
    {
        // Same joins as the marker query below, so the legend counts always
        // match the markers on the map (the old count query also joined
        // "operations", which the marker query did not).
        $mediaCount = DB::table('footages')
            ->join('types', 'footages.typeId', '=', 'types.id')
            ->join('countries', 'footages.countryId', '=', 'countries.id')
            ->join('sources', 'footages.sourceId', '=', 'sources.id')
            ->where('footages.published', true)
            ->groupBy('footages.typeId', 'types.type', 'types.description')
            ->orderBy('footages.typeId')
            ->select('footages.typeId', 'types.type', 'types.description', DB::raw('count(*) as cnt'))
            ->get();

        // Explicit columns: "footages.*, types.*, countries.*, sources.*" made
        // columns with the same name (id, created_at, ...) overwrite each other
        // and printed every column of four tables into the page source.
        $footages = Footage::query()
            ->join('types', 'footages.typeId', '=', 'types.id')
            ->join('countries', 'footages.countryId', '=', 'countries.id')
            ->join('sources', 'footages.sourceId', '=', 'sources.id')
            ->where('footages.published', true)
            ->select(
                'footages.id',
                'footages.name',
                'footages.now_name',
                'footages.now_taken_on',
                'footages.now_credit',
                'footages.typeId',
                'footages.operationId',
                'footages.place',
                'footages.date',
                'footages.info',
                'footages.remark',
                'footages.lat',
                'footages.lng',
                'sources.source',
                'countries.country'
            )
            ->get();

        // Extension of the small copies (webp/jpg), or null: then the page
        // uses the original picture.
        $available = $thumbs->available();
        $footages->each(function (Footage $footage) use ($available) {
            $footage->setAttribute('thumb', $available[$footage->name] ?? null);
            $footage->setAttribute('now_thumb', $footage->now_name ? ($available[$footage->now_name] ?? null) : null);
        });

        // Published tours with their stops in order. Stops whose footage is
        // not published are left out by the page (it only knows published ones).
        $tours = Tour::published()
            ->with(['stops.unit'])
            ->orderBy('sort')
            ->orderBy('title')
            ->get()
            ->map(fn (Tour $tour) => [
                'slug'  => $tour->slug,
                'title' => $tour->title,
                'intro' => $tour->intro,
                'stops' => $tour->stops->map(fn ($stop) => [
                    'footageId' => $stop->footage_id,
                    'title'     => $stop->title,
                    'text'      => $stop->text,
                    'unit'      => $stop->unit?->name,
                    'unitFull'  => $stop->unit?->full_name,
                ])->values(),
            ]);

        return view('index', [
            'title'      => 'WW2: The Presswar',
            'footages'   => $footages,
            'tours'      => $tours,
            'count'      => $mediaCount,
            'lastUpdate' => config('app.last_update'),
        ]);
    }
}
