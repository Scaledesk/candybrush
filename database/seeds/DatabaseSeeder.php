<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
        for($i=0;$i<20;$i++){
            factory('App\Tag')->create();
        }
        for($i=0;$i<100;$i++){
        factory('App\PackagesModel')->create();
            $first_tag=DB::table('candybrush_tags')->first()->candybrush_tags_id;
            $last_tag=DB::table('candybrush_tags')->orderBy('candybrush_tags_id', 'desc')->first()->candybrush_tags_id;
            $first_package=DB::table('candybrush_packages')->first()->id;
            $last_package=DB::table('candybrush_packages')->orderBy('id', 'desc')->first()->id;
           $tag_id=rand($first_tag,$last_tag);
               $package_id=rand($first_package,$last_package);
            DB::table('candybrush_packages_tags')->insert([
                'candybrush_packages_tags_tag_id' =>$tag_id,
                'candybrush_packages_tags_package_id' =>$package_id
            ]);
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
