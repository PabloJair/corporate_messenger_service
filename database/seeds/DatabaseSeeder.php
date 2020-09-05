<?php

use App\Area;
use App\Company;
use App\CompanyModule;
use App\Module;
use App\PermissionUserApp;
use App\Rol;
use App\User;
use App\UserIformationCompanys;
use App\UserStatus;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        PermissionUserApp::truncate();
        CompanyModule::truncate();
        UserIformationCompanys::truncate();

        User::truncate();
        Area::truncate();
        Module::truncate();

        Rol::truncate();
        Company::truncate();
        UserStatus::truncate();


        $this->call(AreaSeeder::class);
        $this->call(CompanySeeder::class);
        $this->call(ModuleSeeder::class);
        $this->call(RolSeeder::class);
        $this->call(UserStatusSeeder::class);

        $this->call(UserSeeder::class);
        $this->call(CompanyModuleSeeder::class);
        $this->call(UserInformationCompanySeeder::class);
        $this->call(PermissionUserAppSeader::class);

        // $this->call(UserSeeder::class);
    }
}
