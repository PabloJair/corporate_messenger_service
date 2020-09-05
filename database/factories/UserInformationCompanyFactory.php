<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Area;
use App\Company;
use App\Rol;
use App\User;
use App\UserIformationCompanys;
use App\UserStatus;
use Faker\Generator as Faker;

$factory->define(UserIformationCompanys::class, function (Faker $faker) {
      return [
          'id_user' => getRandomUserId(),
          'id_company' => getRandomCompanyId(),
          'id_area' => getRandomAreaId(),
          'id_rol' => getRandomRolId(),
          'status_user' => getRandomStatusId(),

      ];
});

function getRandomUserId() {
    $data = User::inRandomOrder()->first();
    return $data->id_user;
}
function getRandomAreaId() {
    $data = Area::inRandomOrder()->first();
    return $data->id_area;
}
function getRandomRolId() {
    $data = Rol::inRandomOrder()->first();
    return $data->id_rol;
}
function getRandomStatusId() {

    return ''.rand(1,7);
}
