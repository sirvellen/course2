<?php

use Illuminate\Database\Seeder;

class DeskSeeder extends Seeder
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
            'username' => 'manager',
            'email' => 'manager@mail.ml',
            'password' => \Illuminate\Support\Facades\Hash::make('manager'),
            'role' => 'manager',
        ]);

        DB::table('desks')->insert([
            'project_creator' => 1,
            'project_name' => 'project',
            'project_description' => 'test project',
            'project_deadline' => '2020-11-01',
        ]);
    }
}
