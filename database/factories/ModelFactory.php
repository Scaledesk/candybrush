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
use Illuminate\Support\Facades\DB;

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'email' => $faker->email,
        'password' => /*bcrypt(str_random(10))*/bcrypt('1234'),
        'remember_token' => str_random(10),
        'confirmed'=>1
    ];
});

//for Categories
$factory->define(App\Category::class,function(Faker\Generator $faker){
    return [
        'candybrush_categories_name'=>$faker->unique()->randomElement(['Health & wellness','Fashion', 'Home Decor','Travel','Rentals','Spaces','Experts','Skill','Doorstep Services'])/*word . ' category'*/,
        'candybrush_categories_parent_id'=>NULL
    ];
});

$factory->define(App\Tag::class,function(Faker\Generator $faker){
//    echo "enter into tags";
    return [
        'candybrush_tags_name'=>$faker->unique(20)->word.' tag',
        'candybrush_tags_description'=>$faker->sentences(3,true)
    ];
//    echo "enter out of tags";
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
        App\Addon::NAME=>$faker->unique(10000)->word.' addon',
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
        App\Bonus::NAME=>$faker->unique(10000)->word.' bonus',
        App\Bonus::PACKAGE_ID=>$faker->numberBetween($first_package,$last_package),
    ];
});
//for PackagesModel
$factory->define(App\PackagesModel::class,function(Faker\Generator $faker)use($factory){

    $first_user=DB::table('users')->first()->id;
    $last_user=DB::table('users')->orderBy('id', 'desc')->first()->id;

    $first_category=DB::table('candybrush_categories')->first()->candybrush_categories_id;
    $last_category=DB::table('candybrush_categories')->orderBy('candybrush_categories_id', 'desc')->first()->candybrush_categories_id;
    return [
        'candybrush_packages_name'=>$faker->realText(random_int(48,64)),
        'candybrush_packages_description'=>$faker->paragraph(random_int(2,3)),
        'candybrush_packages_price'=>$faker->numberBetween(30,500)*10,
        'candybrush_packages_deal_price'=>$faker->numberBetween(20,450)*10,
        'candybrush_packages_available_dates'=>$faker->date($format = 'Y-m-d', $max = 'now'),
        'candybrush_packages_term_condition'=>$faker->paragraphs(3,true),
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

$factory->define(App\UserProfile::class,function(Faker\Generator $faker){
    $first_user=DB::table('users')->first()->id;
    $last_user=DB::table('users')->orderBy('id', 'desc')->first()->id;
    return [
        'candybrush_users_profiles_users_id'=>$faker->unique()->numberBetween($first_user,$last_user),
        'candybrush_users_profiles_name'=>$faker->name,
        'candybrush_users_profiles_mobile'=>9898600000+$faker->randomNumber(3),
        'candybrush_users_profiles_address'=>$faker->address,
        'candybrush_users_profiles_state'=>$faker->randomElement(array('Uttar Pradesh','Uttarakhand','Madhya Pradesh','Jammu & Kashmir','Himachal Pradesh','Punjab','Chandigarh','NCT of Delhi','Rajasthan','Bihar','Sikkim','Maharastra')),
        'candybrush_users_profiles_city'=>$faker->randomElement(array('Bareilly','Delhi','Ghaziabad','Haridwar','Dehradun','Chandigarh','Mohali','Moradabad','Rampur','Gurgaon','Mumbai')),
        'candybrush_users_profiles_pin'=>$faker->randomNumber(6),
        'candybrush_users_profiles_language_known'=>$faker->randomElement(['English','Urdu','Hindi','Marathi']),
        'candybrush_users_profiles_description'=>$faker->sentences(random_int(2,6),true),
        'candybrush_users_profiles_image'=>$faker->imageUrl($width = 640, $height = 480),
        'candybrush_users_profiles_id_proof'=>$faker->randomElement(['Voter_Id','Driving_License','PAN_CARD', 'PASSPORT']),
        'candybrush_users_profiles_social_account_integration'=>$faker->randomElement(['Yes','No']),
        'candybrush_users_profiles_custom_message'=>$faker->sentences(random_int(2,6),true),
        'candybrush_users_profiles_birth_date'=>$faker->dateTimeThisCentury->format('Y-m-d'),
        'candybrush_users_profiles_sex'=>$faker->randomElement(['male','female']),
        'candybrush_users_profiles_commission'=>random_int(10,20)
    ];
});