<?php

use App\Area;
use App\CompanyModule;
use Illuminate\Database\Seeder;

class CompanyModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(CompanyModule::class,10)->create();
    }
}
