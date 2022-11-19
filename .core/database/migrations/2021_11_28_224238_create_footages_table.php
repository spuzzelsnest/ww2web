<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFootagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

    Schema::create('footages', function(Blueprint $table)
    	{
    		$table->increments('id');
			$table->integer('typeId');
			$table->char('name',60);
			$table->char('shortdesc',155);
			$table->text('info');
			$table->float('lat');
			$table->float('lng');
			$table->char('place',150);
			$table->char('country',120);
			$table->date('date');
			$table->char('source',150);
	    		$table->char('remark',120);
			$table->boolean('published');
			$table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('footages');
    }
}
