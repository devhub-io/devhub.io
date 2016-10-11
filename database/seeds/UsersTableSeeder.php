<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'id' => 1,
            'name' => 'admin',
            'email' => 'admin@develophub.net',
            'password' => bcrypt('secret'),
        ]);
        DB::table('users')->insert([
            'id' => 2,
            'name' => 'bot1',
            'email' => 'bot1@develophub.net',
            'password' => bcrypt('secret'),
        ]);
        DB::table('users')->insert([
            'id' => 3,
            'name' => 'bot2',
            'email' => 'bot2@develophub.net',
            'password' => bcrypt('secret'),
        ]);
        DB::table('users')->insert([
            'id' => 4,
            'name' => 'bot3',
            'email' => 'bot3@develophub.net',
            'password' => bcrypt('secret'),
        ]);
        DB::table('users')->insert([
            'id' => 5,
            'name' => 'bot4',
            'email' => 'bot4@develophub.net',
            'password' => bcrypt('secret'),
        ]);
        DB::table('users')->insert([
            'id' => 6,
            'name' => 'bot5',
            'email' => 'bot5@develophub.net',
            'password' => bcrypt('secret'),
        ]);
        DB::table('users')->insert([
            'id' => 7,
            'name' => 'bot6',
            'email' => 'bot6@develophub.net',
            'password' => bcrypt('secret'),
        ]);
        DB::table('users')->insert([
            'id' => 8,
            'name' => 'bot7',
            'email' => 'bot7@develophub.net',
            'password' => bcrypt('secret'),
        ]);
        DB::table('users')->insert([
            'id' => 9,
            'name' => 'bot8',
            'email' => 'bot8@develophub.net',
            'password' => bcrypt('secret'),
        ]);
    }
}
