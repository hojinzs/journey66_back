<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Place;
use Faker\Generator as Faker;

$factory->define(Place::class, function (Faker $faker) {

    $type_array = ['shop', 'lockstand', 'sharing', 'convinience','monument', 'goverment'];

    return [
        //
        'name' => $faker->name,
        'description' => $faker->text(200),
        'like' => $faker->numberBetween(0,200),
        'latitude' => $faker->latitude(35.246150,37.718222),
        'longitude' => $faker->latitude(126.555493,129.130652),
        'thumbnail' => $faker->imageUrl(640,360),
        'type' => $faker->randomElement($type_array),
        'zip_code' => $faker->numberBetween(10000,99999),
        'address1' => $faker->streetAddress(),
        'address2' => $faker->city(),
        'phone_number' => $faker->e164PhoneNumber(),
        'homepage' => $faker->url(),
    ];
});
