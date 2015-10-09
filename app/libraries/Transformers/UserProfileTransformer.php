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
use Dingo\Api\Http\Request;
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
            UserProfile::FIRST_NAME => Input::get('first_name'),
            UserProfile::LAST_NAME => Input::get('last_name'),
            UserProfile::MOBILE => Input::get('mobile'),
            UserProfile::ADDRESS => Input::get('address'),
            UserProfile::STATE => Input::get('state'),
            UserProfile::CITY => Input::get('city'),
            UserProfile::PIN => Input::get('pin'),
            UserProfile::LANGUAGE_KNOWN =>Input::get('language_known'),
            UserProfile::DESCRIPTION => Input::get('description')
        ];
    }
}