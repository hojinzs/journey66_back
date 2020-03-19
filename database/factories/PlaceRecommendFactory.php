<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\PlaceRecommend;
use Faker\Generator as Faker;
use App\User;
use App\Place;

$factory->define(PlaceRecommend::class, function (Faker $faker) {
    return [
        //
        'comment' => $faker->text(200),
        'place_id' => Place::all()->random(1)[0]->id,
        'user_id' => User::all()->random(1)[0]->id,
    ];
});