<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Tag;
use Faker\Generator as Faker;

$factory->define(Tag::class, function (Faker $faker) {

    $type_array=['utility','brand','merchant','others'];
    $icon_array=[
        [
            'prefix' => 'fas',
            'name' => 'biking'
        ],
        [
            'prefix' => 'fas',
            'name' => 'bicycle'
        ],
        [
            'prefix' => 'fas',
            'name' => 'ambulance'
        ],
        [
            'prefix' => 'fab',
            'name' => 'apple'
        ],
        [
            'prefix' => 'fas',
            'name' => 'beer'
        ],
        [
            'prefix' => 'fas',
            'name' => 'building'
        ],
    ];

    $icon = $faker->randomElement($icon_array);

    return [
        //
        'name' => $faker->word,
        'label' => $faker->word,
        'description' => $faker->paragraph(1),
        'color' => $faker->hexColor,
        'icon_prefix' => $icon['prefix'],
        'icon_name' => $icon['name'],
        'type' => $faker->randomElement($type_array),
    ];
});
