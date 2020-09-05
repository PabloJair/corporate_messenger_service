<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Module;
use Faker\Generator as Faker;

$factory->define(Module::class, function (Faker $faker) {
      return [
        'name_module' => $faker->sentence(3),
        'icon_module' => $faker->imageUrl(46,46),
        'description_module' => $faker->text(200),
    ];
});
