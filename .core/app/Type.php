<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Type extends Model {

	protected $table = 'types';
	protected $primaryKey = 'id' ;

	public function footage(){

		return $this->belongsTo('footages','local_key');
	}

}
