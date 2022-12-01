<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use View, App\Footage, App\Type;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Intervention\Image\ImageManager;


class AdminController extends Controller{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(){
        $mediaCount = DB::table('footages')

        ->join('types','footages.typeId','=','types.id')
        ->join('operations','footages.operationId','=','operations.id')
        ->join('countries','footages.countryId','=','countries.id')
        ->join('sources','footages.sourceId','=','sources.id')

        ->select(Db::Raw('count(0) as cnt, type, description'))
        ->groupBy('footages.typeId','types.id')
        ->get();

        $types            = DB::table('types')->get();
        $operations       = DB::table('operations')->get();
        $operationOptions = $operations->pluck('operation', 'id');
        $countries        = DB::table('countries')->get();
        $countryOptions   = $countries->pluck('country', 'id');
        $sources          = DB::table('sources')->get();
        $sourceOptions    = $sources->pluck('source', 'id');

        return View::make('admin')
            ->with('title', 'Edit the Database')
            ->with('footages', Footage::all())
            ->with('count', $mediaCount)
            ->with('types', $types)
            ->with('operationOptions', $operationOptions)
            ->with('countryOptions', $countryOptions)
            ->with('sourceOptions', $sourceOptions);     
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request){
        $input = $request->all();

        $input['date'] = date('Y-m-d', strtotime($input['date']));

        $v = Validator::make($input, Footage::$rules);

        if ($v->passes()) {

            $f = new Footage;
            $f->name        = $request->name;
            $f->info        = $request->info;
            $f->operationId = $request->operationId;
            $f->date        = $request->date;
            $f->place       = $request->place;
            $f->countryId   = $request->countryId;
            $f->sourceId    = $request->sourceId;
            $f->remark      = $request->remark;
            $f->typeId      = $request->typeId;
            $f->lat         = $request->lat;
            $f->lng         = $request->lng;
            $f->published   = $request->published;
            $f->save();

            //Image::make(Input::file('file') ->getRealPath())
            //    ->resize(540, null, function ($constraint) {
            //            $constraint->aspectRatio();
            //})
            //->save('images/' . $f->name . '.jpg');

        return Redirect::route('admin.index');
        }
        return Redirect::back()->withErrors($v);
}

/**
* Display the specified resource.
*
* @param  int  $id
* @return Response
*/
public function show($id)
{
//
}

/**
* Show the form for editing the specified resource.
*
* @param  int  $id
* @return Response
*/
public function edit($id)
{
//
}

/**
* Update the specified resource in storage.
*
* @param  Request  $request

 * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
