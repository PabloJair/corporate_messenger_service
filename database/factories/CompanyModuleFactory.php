<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Company;
use App\CompanyModule;
use App\Module;
use Faker\Generator as Faker;

$factory->define(CompanyModule::class, function (Faker $faker) {
      return [
        'id_company' => getRandomCompanyId(),
        'id_module' => getRandomModuleId(),
    ];
});

 function getRandomCompanyId() {
    $data = Company::inRandomOrder()->first();
    return $data->id_company;
}
function getRandomModuleId() {
    $data = Module::inRandomOrder()->first();
    return $data->id_module;
}
