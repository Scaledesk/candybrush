<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        for($i=0;$i<30;$i++){
            factory('App\User')->create();

        }// after this user id has to be changed in sequence otherwise foreign key error
        for($i=0;$i<20;$i++){
            factory('App\Category')->create();

        }
        for($i=0;$i<100;$i++){
        factory('App\PackagesModel')->create();
        }
        for($i=0;$i<200;$i++){
            factory('App\PackagePhoto')->create();
        }
        for($i=0;$i<200;$i++){
        factory('App\Addon')->create();
        }
        for($i=0;$i<200;$i++){
            factory('App\Bonus')->create();
        }
        // tags remains
        Model::reguard();
    }
}
