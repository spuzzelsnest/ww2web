<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use View, App\Footage, App\Type;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Intervention\Image\ImageManager;
use Image;


class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
		$mediaCount = DB::table('footages')
				->select(Db::Raw('count(0) as cnt, type, description'))
				->join('types','footages.typeId','=','types.id')
				->groupBy('footages.typeId')
				->get();

//		$types = Type::lists('id', 'type', 'description')->all();

		$types = DB::table('types')->get();

		return View::make('admin')
			->with('title', 'Edit the Database')
			->with('footages', Footage::all())
			->with('count', $mediaCount)
			->with('types', $types);
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
    public function store(Request $request)
    {
	$input = Input::all();

	$input['date'] = date('Y-m-d', strtotime($input['date']));

		$v = Validator::make($input, Footage::$rules);

		if ($v->passes()) {

			$f = new Footage;
			$f->name = Input::get('name');
			$f->info = Input::get('info');
			$f->shortdesc = Input::get('shortdesc');
			$f->date = Input::get('date');
			$f->place = Input::get('place');
			$f->country = Input::get('country');
			$f->source = Input::get('source');
			$f->remarks = Input::get('remarks');
			$f->typeId = Input::get('typeId');
			$f->lat = Input::get('lat');
			$f->lng = Input::get('lng');
			$f->published = Input::get('published');
			$f->save();


			Image::make(Input::file('file')	->getRealPath())
					->resize(540, null, function ($constraint) {
					$constraint->aspectRatio();
						})
					->save('images/' . $f->name . '.jpg');

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
