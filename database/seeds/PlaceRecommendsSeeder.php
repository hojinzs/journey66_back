<?php

use Illuminate\Database\Seeder;
use App\PlaceRecommend;

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
                $placeRecommend->make();
            });
    }
}
