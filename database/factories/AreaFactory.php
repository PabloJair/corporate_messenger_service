<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(App\Area::class, function (Faker $faker) {
      return [
        'name_area' => $faker->sentence(3),
        'icon_area' => $faker->imageUrl(),
        'description_area' => $faker->text(200),
    ];
});
