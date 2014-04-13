<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCategoriesTable extends Migration {

	public function up()
	{
		Schema::create('categories', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
            $table->integer('mod_by_id')->unsigned();
            $table->integer('created_by_id')->unsigned();
			$table->string('name', 63)->unique();
            $table->engine = 'InnoDB';

        });
	}

	public function down()
	{
		Schema::drop('categories');
	}
}