<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Footage extends Model {

	protected $table = 'footages';
	protected $primaryKey = 'id' ;

	public function mediaType(){
		return $this->hasOne('type');
	}

	public function getOperation(){
		return $this->hasOne('Operation');
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

	public function setDatetAttribute($value)
	{
//	$this->attributes['date'] =	Date::createFromFormat('Y/m/d', $value);
	$this->attributes['date'] =	Carbon::createFromFormat('Y/m/d', $value)->toDateTimeString();
	}
	public static $rules = array(
			'typeId'=> 'required',
			'name' => 'required',
			'place' => 'required',
			'lat' => 'required',
			'lng' => 'required',
	);
}
