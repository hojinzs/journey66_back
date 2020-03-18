<?php

use Illuminate\Database\Seeder;
use App\Place;

class PlacesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        factory(Place::class, 50)->create()->each(function ($place) {
            $place->make();
        });
    }
}
