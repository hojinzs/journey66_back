<?php

use Illuminate\Database\Seeder;

class PlaceTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        factory(\App\PlaceType::class,6)
            ->create();
    }
}
