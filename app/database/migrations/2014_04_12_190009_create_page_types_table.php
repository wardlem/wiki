<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;


class CreatePageTypesTable extends Migration {

    public function up()
    {
        Schema::create('page_types', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('url_prefix')->unique();
            $table->integer('mod_by_id')->unsigned();
            $table->integer('created_by_id')->unsigned();
            $table->timestamps();
            $table->engine = 'InnoDB';
        });
    }

    public function down()
    {
        Schema::drop('pages');
    }
}