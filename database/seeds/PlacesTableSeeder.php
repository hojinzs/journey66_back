<?php

use App\Tag;
use Illuminate\Database\Seeder;
use App\Place;
use App\User;
use App\Like;
use App\PlaceTagComment;
use Faker\Factory as Faker;

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
        factory(Place::class, 20)
            ->create()
            ->each(function ($place) {
                $faker = Faker::create();
                $tags = Tag::all();
                $users = User::all();

                // Set Tag Comments
                foreach ($tags->random(3,6) as $tag) {
                    $rand = rand(5,20);
                    for ($i = 0; $i < $rand ; $i++) {
                        $tagComment = new PlaceTagComment;
                        $tagComment->place()->associate($place);
                        $tagComment->tag()->associate($tag);
                        $tagComment->user()->associate($users->random(1)->first());
                        $tagComment->content = $faker->text(200);
                        $tagComment->push();
                    }
                }

                // Set User Like
                $users = User::all()->random(5,10);
                foreach ($users as $user) {
                    $like = new Like;
                    $like->user()->associate($user);

                    $place->likes()->save($like);
                }
            });
    }
}
