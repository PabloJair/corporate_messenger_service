<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(App\UserStatus::class, function (Faker $faker) {
      return [
        'name_status_user' => $faker->sentence(3),
        'description_status_user' => $faker->text(200),
    ];
});
