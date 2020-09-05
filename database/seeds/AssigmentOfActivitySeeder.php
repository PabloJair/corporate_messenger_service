<?php

use Illuminate\Database\Seeder;

class AssigmentOfActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\AssigmentOfActivity::class,10)->create();

    }
}
