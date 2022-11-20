<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
    protected $table = 'sources';
	protected $primaryKey = 'id' ;
    //use HasFactory;

    public function source(){

        return $this->belongsTo('footages','local_key');
    }
}
