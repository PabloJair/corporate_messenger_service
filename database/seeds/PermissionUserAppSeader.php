<?php

use App\Area;
use App\PermissionUserApp;
use Illuminate\Database\Seeder;

class PermissionUserAppSeader extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(PermissionUserApp::class,10)->create();
    }
}
