<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\PlaceType;
use Faker\Generator as Faker;

$factory->define(PlaceType::class, function (Faker $faker) {
    return [
        //
        'name' => $faker->text(10),
        'label' => $faker->text(12),
        'description' => $faker->paragraph(1),
    ];
});
