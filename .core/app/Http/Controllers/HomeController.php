<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB , View, App\Footage, App\Type, Illuminate\Support\Facades\Input;
class HomeController extends Controller{
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

			->select(DB::Raw('count(0) as cnt, typeId, type, description, published'))->where('published', '=', '1')
			->groupBy('footages.typeId','types.type','types.description','footages.published')
			->get();

		return View::make('index')
			->with('title' , 'WW2: The Presswar')
			->with('footages', Footage::where('published', '=', '1')
						->join('countries', 'footages.countryId', '=', 'countries.id')
						->join('types','footages.typeId', '=', 'types.id')
						->join('sources','footages.sourceId', '=', 'sources.id')
						->get(array('footages.*','types.*', 'countries.*', 'sources.*')))
			->with('count', $mediaCount)
			->with('lastUpdate', '03/06/2023');
    }
}
