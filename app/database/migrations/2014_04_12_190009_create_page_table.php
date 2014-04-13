<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePageTable extends Migration {

	public function up()
	{
		Schema::create('pages', function(Blueprint $table) {
			$table->increments('id');
            $table->integer('page_type_id');
            $table->integer('category_id')->unsigned()->nullable();
			$table->string('title')->unique();
			$table->string('slug')->unique();
			$table->text('content');
            $table->integer('mod_by_id')->unsigned();
            $table->integer('created_by_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();
            $table->engine = 'InnoDB';
        });
	}

	public function down()
	{
		Schema::drop('pages');
	}
}