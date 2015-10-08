<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class UserProfile extends Model
{
    //

    const TABLE = 'users_profiles';
    const CANDYBRUSH_USERS_PROFILES_USER_ID = 'candybrush_users_profiles_user_id';
    const CANDYBRUSH_USERS_PROFILES_FIRST_NAME = 'candybrush_users_profiles_first_name';
    const CANDYBRUSH_USERS_PROFILES_LAST_NAME = 'candybrush_users_profiles_last_name';
    const CANDYBRUSH_USERS_PROFILES_MOBILE = 'candybrush_users_profiles_mobile';
    const CANDYBRUSH_USERS_PROFILES_ADDRESS = 'candybrush_users_profiles_address';
    const CANDYBRUSH_USERS_PROFILES_STATE = 'candybrush_users_profiles_state';
    const CANDYBRUSH_USERS_PROFILES_CITY = 'candybrush_users_profiles_city';
    const CANDYBRUSH_USERS_PROFILES_PIN = 'candybrush_users_profiles_pin';
    const CANDYBRUSH_USERS_PROFILES_LANGUAGE_KNOWN = 'candybrush_users_profiles_language_known';
    const CANDYBRUSH_USERS_PROFILES_DESCRIPTION = 'candybrush_users_profiles_description';
    const CANDYBRUSH_USERS_PROFILES_IMAGE = 'candybrush_users_profiles_image';


    protected $table = self::TABLE;

    protected $fillable = [self::CANDYBRUSH_USERS_PROFILES_USER_ID, self::CANDYBRUSH_USERS_PROFILES_FIRST_NAME, self::CANDYBRUSH_USERS_PROFILES_LAST_NAME,
        self::CANDYBRUSH_USERS_PROFILES_MOBILE, self::CANDYBRUSH_USERS_PROFILES_ADDRESS, self::CANDYBRUSH_USERS_PROFILES_STATE, self::CANDYBRUSH_USERS_PROFILES_CITY,
        self::CANDYBRUSH_USERS_PROFILES_PIN];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
