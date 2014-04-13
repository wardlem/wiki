<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCommentsTable extends Migration {

	public function up()
	{
		Schema::create('comments', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('page_id')->unsigned()->nullable();
			$table->integer('parent_comment_id')->unsigned()->nullable();
			$table->integer('user_id')->unsigned();
			$table->text('content');
            $table->timestamps();
            $table->softDeletes();
            $table->engine = 'InnoDB';

        });
	}

	public function down()
	{
		Schema::drop('comments');
	}
}