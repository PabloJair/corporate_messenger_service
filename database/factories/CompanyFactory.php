<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Company;
use Faker\Generator as Faker;

$factory->define(Company::class, function (Faker $faker) {
    return [
        'name_company' => $faker->sentence(3),
        'logotype_company' => $faker->imageUrl(),
        'description_company' => $faker->text(200),
    ];
});
