<?php

use Illuminate\Database\Seeder;
use App\Place;
use App\Tag;
use App\User;
use App\Like;

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
        factory(Place::class, 20)->create()->each(function ($place) {
            $tags = Tag::all();
            $users = User::all()->random(5,10);

            $place->tags()->attach($tags->random(rand(1,5)));

            foreach ($users as $user)
            {
                $like = new Like;
                $like->user()->associate($user);

                $place->likes()->save($like);
            }
        });
    }
}
