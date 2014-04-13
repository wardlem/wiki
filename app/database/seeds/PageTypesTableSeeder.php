<?php

use Illuminate\Database\Seeder;

class PageTypesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('page_types')->delete();

        $user = User::where('email', 'like', 'mwwardle@gmail.com')->firstOrFail();

        PageType::create(array(
            'name' => 'Article',
            'url_prefix' => 'article',
            PageType::MODIFIED_BY => $user->id,
            PageType::CREATED_BY => $user->id,
        ));
        PageType::create(array(
            'name' => 'Media',
            'url_prefix' => 'media',
            PageType::MODIFIED_BY => $user->id,
            PageType::CREATED_BY => $user->id,
        ));

        $this->command->info('Pages table seeded.');
    }
}