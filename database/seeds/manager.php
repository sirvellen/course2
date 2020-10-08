<?php

use Illuminate\Database\Seeder;

class manager extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'username' => 'manager',
            'email' => 'manager@mail.ru',
            'password' => 'manager',
            'role' => 'manager',
        ]);
    }
}
