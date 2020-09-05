<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Area;
use App\Company;
use App\Rol;
use App\User;
use App\UserIformationCompanys;
use App\UserStatus;
use Faker\Generator as Faker;

$factory->define(\App\AssigmentOfActivity::class, function (Faker $faker) {
      return [
          'id_user'=>getRandomUserId(),
          'type_activity' => ''.rand(1,5),
          'status_activity' =>1,
          'start_date' =>$faker->dateTimeBetween('now','+7 days'),
          'end_date'=>$faker->dateTimeBetween('now','+7 days'),
          'start_time'=>$faker->time('H:i','7:00'),
          'end_time'=>$faker->time('H:i','7:00'),
          'notes'=>$faker->text,
          'stated_date'=>null,
          'finish_date'=>null];
});


