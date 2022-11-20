<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = 'countries';
	protected $primaryKey = 'id' ;
    //use HasFactory;
	public function countrie(){

		return $this->belongsTo('footages','local_key');
	}
}
