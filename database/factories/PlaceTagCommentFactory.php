<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Place;
use App\PlaceTagComment;
use App\Tag;
use App\User;
use Faker\Generator as Faker;

$factory->afterMaking(PlaceTagComment::class,function ($place_tag_comment){
    $tags = Tag::all();
    $places = Place::all();
    $users = User::all();

    $get_tag = $tags->random(1)->first();

    Log::info('call-Tags');
    Log::info($get_tag->id);

    $place_tag_comment->prepareTag($get_tag);
    $place_tag_comment->preparePlace($places->random(1)->first());
    $place_tag_comment->user()->associate($users->random(1)->first());

    Log::info($place_tag_comment->prepare_tag);
});

$factory->define(PlaceTagComment::class, function (Faker $faker) {
    return [
        //
        'content' => $faker->text(200),
    ];
});
