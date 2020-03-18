<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Tag;
use Faker\Generator as Faker;

$factory->define(Tag::class, function (Faker $faker) {

    $type_array=['utility','brand','merchant','others'];
    $icon_array=['biking','bicycle','ambulance','apple','beer','building'];

    return [
        //
        'name' => $faker->word,
        'label' => $faker->word,
        'description' => $faker->paragraph(1),
        'color' => $faker->hexColor,
        'icon' => $faker->randomElement($icon_array),
        'type' => $faker->randomElement($type_array),
    ];
});
