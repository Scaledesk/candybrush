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
        $truncate=function(){
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            $tableNames = Schema::getConnection()->getDoctrineSchemaManager()->listTableNames();
            foreach ($tableNames as $name) {
                //if you don't want to truncate migrations
                if ($name == 'migrations') {
                    continue;
                }
                DB::table($name)->truncate();
            }
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        };
        $truncate();
        for($i=0;$i<30;$i++){
            factory('App\User')->create();

        }// after this user id has to be changed in sequence otherwise foreign key error
        for($i=0;$i<9;$i++){
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
            $first_user=DB::table('users')->first()->id;
            $last_user=DB::table('users')->orderBy('id', 'desc')->first()->id;
            $faker= Faker\Factory::create();
            for($j=0;$j<4;$j++){
            Db::table('candybrush_reviews')->insert([
                App\ReviewModel::USER_ID=>random_int($first_user,$last_user),
                App\ReviewModel::PACKAGE_ID=>$i,
                App\ReviewModel::ADMIN_VERIFIED=>array_rand(array('yes','no')),
                App\ReviewModel::COMMENT=>$faker->realText(random_int(10,20)),
                App\ReviewModel::RATING=>random_int(1,5),
            ]);
            }
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
        for($i=0;$i<30;$i++){
            factory('App\UserProfile')->create();
        }
        Model::reguard();
    }
}
