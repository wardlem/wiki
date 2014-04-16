<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	public function up()
	{
		Schema::create('users', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->softDeletes();
			$table->string('email', 63)->unique();
			$table->string('password', 255);
			$table->string('username', 63)->unique();
            $table->string('remember_token', 100)->nullable();
            $table->engine = 'InnoDB';

        });
	}

	public function down()
	{
		Schema::drop('users');
	}
}