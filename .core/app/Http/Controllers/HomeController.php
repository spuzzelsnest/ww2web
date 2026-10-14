<?php

namespace App\Http\Controllers;

use App\Footage;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
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

        return view('index', [
            'title'      => 'WW2: The Presswar',
            'footages'   => $footages,
            'count'      => $mediaCount,
            'lastUpdate' => config('app.last_update'),
        ]);
    }
}
