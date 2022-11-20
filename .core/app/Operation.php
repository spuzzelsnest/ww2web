<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    protected $table = 'operations';
	protected $primaryKey = 'id' ;
    //use HasFactory;
    public function operation(){

		return $this->belongsTo('footages','local_key');
	}
}
