<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

        DB::table('comments')->delete();
        DB::table('revisions')->delete();
        DB::table('pages')->delete();
        DB::table('categories')->delete();

		$this->call('UserTableSeeder');
        $this->call('PageTypesTableSeeder');
        $this->call('TestDataSeeder');
	}

}