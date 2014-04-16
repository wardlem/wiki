<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRevisionsTable extends Migration {

	public function up()
	{
		Schema::create('revisions', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('page_id')->unsigned();
            $table->integer('created_by_id')->unsigned();
            $table->text('content');
            $table->timestamps();
            $table->softDeletes();
            $table->engine = 'InnoDB';
        });
	}

	public function down()
	{
		Schema::drop('revisions');
	}
}