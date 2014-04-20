<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateForeignKeys extends Migration {

    public function up()
    {
        Schema::table('pages', function(Blueprint $table) {
            $table->foreign('mod_by_id')->references('id')->on('users')
                ->onDelete('restrict')
                ->onUpdate('cascade');
        });
        Schema::table('pages', function(Blueprint $table) {
            $table->foreign('created_by_id')->references('id')->on('users')
                ->onDelete('restrict')
                ->onUpdate('cascade');
        });
        Schema::table('pages', function(Blueprint $table) {
            $table->foreign('category_id')->references('id')->on('categories')
                ->onDelete('set null')
                ->onUpdate('cascade');
        });
        Schema::table('categories', function(Blueprint $table) {
            $table->foreign('mod_by_id')->references('id')->on('users')
                ->onDelete('restrict')
                ->onUpdate('cascade');
        });
        Schema::table('categories', function(Blueprint $table) {
            $table->foreign('created_by_id')->references('id')->on('users')
                ->onDelete('restrict')
                ->onUpdate('cascade');
        });
        Schema::table('comments', function(Blueprint $table) {
            $table->foreign('page_id')->references('id')->on('pages')
                ->onDelete('set null')
                ->onUpdate('cascade');
        });
        Schema::table('comments', function(Blueprint $table) {
            $table->foreign('parent_comment_id')->references('id')->on('comments')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
        Schema::table('comments', function(Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('restrict')
                ->onUpdate('cascade');
        });
        Schema::table('revisions', function(Blueprint $table) {
            $table->foreign('page_id')->references('id')->on('pages')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
        Schema::table('revisions', function(Blueprint $table) {
            $table->foreign('created_by_id')->references('id')->on('users')
                ->onDelete('restrict')
                ->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::table('pages', function(Blueprint $table) {
            $table->dropForeign('pages_mod_by_id_foreign');
            $table->dropForeign('pages_created_by_id_foreign');
            $table->dropForeign('pages_category_id_foreign');

        });

        Schema::table('categories', function(Blueprint $table) {
            $table->dropForeign('categories_mod_by_id_foreign');
            $table->dropForeign('categories_created_by_id_foreign');
        });

        Schema::table('comments', function(Blueprint $table) {
            $table->dropForeign('comments_page_id_foreign');
            $table->dropForeign('comments_parent_comment_id_foreign');
            $table->dropForeign('comments_user_id_foreign');
        });

        Schema::table('revisions', function(Blueprint $table) {
            $table->dropForeign('revisions_page_id_foreign');
            $table->dropForeign('revisions_created_by_id_foreign');

        });

    }
}