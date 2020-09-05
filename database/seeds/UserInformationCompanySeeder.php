<?php

use App\Area;
use App\UserIformationCompanys;
use Illuminate\Database\Seeder;

class UserInformationCompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(UserIformationCompanys::class,10)->create();
    }
}
