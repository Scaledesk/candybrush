<?php
/*
*
 * Created by PhpStorm.
 * User: Javed
 * Date: 7/10/15
 * Time: 7:23 PM
 */

namespace App\libraries\Transformers;



use App\UserProfile;
use Illuminate\Support\Facades\Input;
use League\Fractal\TransformerAbstract;

class UserProfileTransformer extends TransformerAbstract{

    public function transform(UserProfile $profile){
        return [
            'first_name'=>$profile->candybrush_users_profiles_first_name,
            'last_name'=>$profile->candybrush_users_profiles_last_name
        ];
    }
    public function requestAdapter()
    {

        return [
            UserProfile::CANDYBRUSH_USERS_PROFILES_FIRST_NAME => Input::get(UserProfile::CANDYBRUSH_USERS_PROFILES_FIRST_NAME),
            UserProfile::CANDYBRUSH_USERS_PROFILES_LAST_NAME => Input::get(UserProfile::CANDYBRUSH_USERS_PROFILES_LAST_NAME),
            UserProfile::CANDYBRUSH_USERS_PROFILES_MOBILE => Input::get(UserProfile::CANDYBRUSH_USERS_PROFILES_MOBILE),
            UserProfile::CANDYBRUSH_USERS_PROFILES_ADDRESS => Input::get(UserProfile::CANDYBRUSH_USERS_PROFILES_ADDRESS),
            UserProfile::CANDYBRUSH_USERS_PROFILES_STATE => Input::get(UserProfile::CANDYBRUSH_USERS_PROFILES_STATE),
            UserProfile::CANDYBRUSH_USERS_PROFILES_CITY => Input::get(UserProfile::CANDYBRUSH_USERS_PROFILES_CITY),
            UserProfile::CANDYBRUSH_USERS_PROFILES_PIN => Input::get(UserProfile::CANDYBRUSH_USERS_PROFILES_PIN)
        ];
    }
}