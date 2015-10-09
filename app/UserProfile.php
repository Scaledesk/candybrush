<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class UserProfile extends Model
{
    //

    const TABLE = 'users_profiles';
    const USER_ID = 'candybrush_users_profiles_user_id';
    const FIRST_NAME = 'candybrush_users_profiles_first_name';
    const LAST_NAME = 'candybrush_users_profiles_last_name';
    const MOBILE = 'candybrush_users_profiles_mobile';
    const ADDRESS = 'candybrush_users_profiles_address';
    const STATE = 'candybrush_users_profiles_state';
    const CITY = 'candybrush_users_profiles_city';
    const PIN = 'candybrush_users_profiles_pin';
    const LANGUAGE_KNOWN = 'candybrush_users_profiles_language_known';
    const DESCRIPTION = 'candybrush_users_profiles_description';
    const IMAGE = 'candybrush_users_profiles_image';


    protected $table = self::TABLE;

    protected $fillable = [self::FIRST_NAME, self::LAST_NAME, self::MOBILE, self::ADDRESS, self::STATE, self::CITY, self::PIN, self::LANGUAGE_KNOWN, self::DESCRIPTION,self::IMAGE];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
