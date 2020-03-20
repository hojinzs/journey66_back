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
                for ($i = 0; $i < 5; $i++){
                    foreach ($tags->random(3,5) as $tag) {
                        $tagComment = new PlaceTagComment;
                        $tagComment->prepareTag($tag);
                        $tagComment->preparePlace($place);
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
