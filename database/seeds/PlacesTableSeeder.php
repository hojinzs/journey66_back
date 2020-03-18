<?php

use Illuminate\Database\Seeder;
use App\Place;
use App\Tag;

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
            $tags = Tag::all();

            $place->tags()->attach($tags->random(rand(1,5)));
        });
    }
}
