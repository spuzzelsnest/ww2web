<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('footages', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('typeId');
            $table->integer('operationId');
			$table->char('name',60);
			$table->text('info');
			$table->float('lat');
			$table->float('lng');
			$table->char('place',100);
			$table->integer('countryId',1);
			$table->date('date');
			$table->integer('sourceId',1);
	    	$table->char('remark',120)->nullable(true);
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
};