<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\CompanyModule;
use App\Module;
use App\PermissionUserApp;
use App\UserIformationCompanys;
use Faker\Generator as Faker;

$factory->define(PermissionUserApp::class, function (Faker $faker) {
    return [
        'id_info_user_company' => getRandomInfoUserCompanyId(),
        'id_company_module' => getRandomCompanyModuleId(),
        'can_delete'=> $faker->boolean,'can_update'=>$faker->boolean,'can_select'=>$faker->boolean,'can_create'=>$faker->boolean
    ];
});
function getRandomInfoUserCompanyId() {
    $data = UserIformationCompanys::inRandomOrder()->first();
    return $data->id_company;
}
function getRandomCompanyModuleId() {
    $data = CompanyModule::inRandomOrder()->first();
    return $data->id_module;
}
