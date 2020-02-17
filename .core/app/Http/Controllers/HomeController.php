<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB , View, App\Footage, App\Type, Illuminate\Support\Facades\Input;


class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {

		$mediaCount = DB::table('footages')
			->select(DB::Raw('count(0) as cnt, typeId, type, description, published'))->where('published', '=', '1')
			->join('types','footages.typeId','=','types.id')
			->groupBy('footages.typeId')
			->get();

		return View::make('index')
			->with('title' , 'WW2: The Presswar')
			->with('footages', Footage::where('published', '=', '1')->join('types','footages.typeId', '=', 'types.id')->get(array('footages.*','types.*')))
			->with('count', $mediaCount);
}


}
