<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Footage extends Model {

	protected $table = 'footages';
	protected $primaryKey = 'id' ;

	public function mediaType(){
		return $this->hasOne('type');
	}

	public function getOperation(){
		return $this->hasOne('operation');
	}

	public function getCountry(){
		return $this->hasOne('country');
	}

	public function getSource(){
		return $this->hasOne('source');
	}

	public function getDates(){
		return array('created_at', 'updated_at');
	}

	public function setDateAttribute($value){
		$this->attributes['date'] =	Carbon::createFromFormat('Y/m/d', $value)->toDateTimeString();
	}
	
	public static $footagesRules = array(
			'name'  => 'required|unique:footages',
			'typeId'=> 'required',
			'place' => 'required',
			'lat'   => 'required',
			'lng'   => 'required'
	);
}
