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
        DB::table('desks')->insert([
            'desk_name' => 'desk',
        ]);
    }
}
