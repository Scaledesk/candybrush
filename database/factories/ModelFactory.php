<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/
$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

//for Categories
$factory->define(App\Category::class,function(Faker\Generator $faker){
    return [
        'candybrush_categories_name'=>$faker->unique(20)->name . ' category',
        'candybrush_categories_parent_id'=>NULL
    ];
});

//for addons
$factory->define(App\Addon::class,function(Faker\Generator $faker){
    $packages = \Illuminate\Support\Facades\DB::table('candybrush_packages')->get(['id']);
    $package_data=array();
    foreach($packages as $package){
        $package_data[]=$package->id;
    }
    $packages=$package_data;
    $first_package=reset($packages);
    $last_package=end($packages);
    unset($package_data);
    unset($packages);
    return [
        App\Addon::DESCRIPTION=>$faker->realText(100),
        App\Addon::NAME=>$faker->unique(10000)->name.' addon',
        App\Addon::DAYS=>$faker->numberBetween(0,10),
        App\Addon::PACKAGE_ID=>$faker->numberBetween($first_package,$last_package),
        App\Addon::PRICE=>$faker->numberBetween(1,20)
    ];
});

//for bonus
$factory->define(App\Bonus::class,function(Faker\Generator $faker){
    $packages = \Illuminate\Support\Facades\DB::table('candybrush_packages')->get(['id']);
    $package_data=array();
    foreach($packages as $package){
        $package_data[]=$package->id;
    }
    $packages=$package_data;
    $first_package=reset($packages);
    $last_package=end($packages);
    unset($package_data);
    unset($packages);
    return [
        App\Bonus::DESCRIPTION=>$faker->realText(100),
        App\Bonus::NAME=>$faker->unique(10000)->name.' bonus',
        App\Bonus::PACKAGE_ID=>$faker->numberBetween($first_package,$last_package),
    ];
});
//for PackagesModel
$factory->define(App\PackagesModel::class,function(Faker\Generator $faker)use($factory){
    $users = \Illuminate\Support\Facades\DB::table('users')->get(['id']);
    $user_data=array();
    foreach($users as $user){
        $user_data[]=$user->id;
    }
    $users=$user_data;
    $first_user=reset($users);
    $last_user=end($users);
    unset($user_data);
    unset($users);
    $categories =
        \Illuminate\Support\Facades\DB::table('candybrush_categories')->get(['candybrush_categories_id']);
    $category_data=array();
    foreach($categories as $category){
        $category_data[]=$category->candybrush_categories_id;
    }
    $categories=$category_data;
    $first_category=reset($categories);
    $last_category=end($categories);

    unset($category_data);
    unset($category);
    return [
        'candybrush_packages_name'=>$faker->name.' package',
        'candybrush_packages_description'=>$faker->realText(200),
        'candybrush_packages_price'=>$faker->numberBetween(30,50),
        'candybrush_packages_deal_price'=>$faker->numberBetween(20,45),
        'candybrush_packages_available_dates'=>$faker->date($format = 'Y-m-d', $max = 'now'),
        'candybrush_packages_term_condition'=>$faker->realText(100),
        'candybrush_packages_payment_type'=>$faker->randomElement(['money_transfer',' cod','paypal','direct_transfer']),
        'candybrush_packages_maximum_delivery_days'=>$faker->randomElement(['1','2','3','4','5','6','7','8','9','10']),
        'candybrush_packages_category_id'=>$faker->numberBetween($first_category,$last_category),
        'candybrush_packages_user_id'=>$faker->numberBetween($first_user,$last_user),
        'candybrush_packages_status'=>$faker->randomElement(['ACTIVE','PENDING_APPROVAL','REQUIRES_MODIFICATION','DENIED','PAUSED'])
    ];
});
$factory->define(App\PackagePhoto::class,function(Faker\Generator $faker){
    $packages = \Illuminate\Support\Facades\DB::table('candybrush_packages')->get(['id']);
    $package_data=array();
    foreach($packages as $package){
        $package_data[]=$package->id;
    }
    $packages=$package_data;
    $first_package=reset($packages);
    $last_package=end($packages);
    return[
        'candybrush_packages_photos_url'=>$faker->imageUrl($width = 640, $height = 480),
        'candybrush_packages_photos_packages_id'=>$faker->numberBetween($first_package,$last_package)
    ];
});
