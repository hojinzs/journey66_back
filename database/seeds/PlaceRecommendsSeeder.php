<?php

use Illuminate\Database\Seeder;
use App\PlaceRecommend;
use App\User;
use App\Like;

class PlaceRecommendsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        factory(PlaceRecommend::class, 300)
            ->create()
            ->each(function($placeRecommend){
                $users = User::all();

                // set Recommend like
                foreach ($users->random(rand(0,10)) as $user){
                    $like = new Like;
                    $like->user()->associate($user);

                    $placeRecommend->likes()->save($like);
                }
            });
    }
}
