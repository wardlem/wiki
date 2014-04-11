<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->delete();

        User::create(array(
            'email' => 'mystik3eb@gmail.com',
            'username' => 'Dan',
            'password' => Hash::make('ocarina'),
        ));

        User::create(array(
            'email' => 'mwwardle@gmail.com',
            'username' => 'Mark',
            'password' => Hash::make('vader'),
        ));

        $this->command->info('User table seeded.');
    }
}