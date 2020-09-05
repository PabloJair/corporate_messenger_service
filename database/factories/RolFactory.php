<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(App\Rol::class, function (Faker $faker) {
      return [
        'name_rol' => $faker->sentence(3),
        'description_rol' => $faker->text(200),
    ];
});
