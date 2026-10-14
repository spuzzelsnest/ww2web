<?php

namespace App\Http\Controllers;

use App\Footage;
use App\Http\Requests\StoreFootageRequest;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * The "add footage" form.
     *
     * The old version also loaded every footage row and a count query that the
     * view never used.
     */
    public function index()
    {
        return view('admin', [
            'title'            => 'Edit the Database',
            'types'            => DB::table('types')->get(),
            'operationOptions' => DB::table('operations')->pluck('operation', 'id'),
            'countryOptions'   => DB::table('countries')->orderBy('country')->pluck('country', 'id'),
            'sourceOptions'    => DB::table('sources')->pluck('source', 'id'),
        ]);
    }

    /**
     * Store a new footage record and its picture.
     */
    public function store(StoreFootageRequest $request)
    {
        $data = $request->validated();

        unset($data['image']);

        // Empty text fields arrive as null (ConvertEmptyStringsToNull) but the
        // info column is NOT NULL; an unchecked checkbox sends nothing at all.
        $data['info']      = $data['info'] ?? '';
        $data['remark']    = $data['remark'] ?? '';
        $data['published'] = $request->boolean('published');

        $filename = $data['name'] . '.jpg';

        // Row and file succeed together or not at all.
        DB::transaction(function () use ($data, $request, $filename) {
            Footage::create($data);

            $request->file('image')->move(public_path('images'), $filename);
        });

        return redirect()
            ->route('admin.index')
            ->with('status', 'Footage "' . $data['name'] . '" was added.');
    }
}
