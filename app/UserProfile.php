<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class UserProfile extends Model
{
    // define constants
    const TABLE = 'users_profiles';
    const USER_ID = 'candybrush_users_profiles_user_id';
    const NAME = 'candybrush_users_profiles_name';
    const MOBILE = 'candybrush_users_profiles_mobile';
    const ADDRESS = 'candybrush_users_profiles_address';
    const STATE = 'candybrush_users_profiles_state';
    const CITY = 'candybrush_users_profiles_city';
    const PIN = 'candybrush_users_profiles_pin';
    const LANGUAGE_KNOWN = 'candybrush_users_profiles_language_known';
    const DESCRIPTION = 'candybrush_users_profiles_description';
    const IMAGE = 'candybrush_users_profiles_image';
    const ID_PROOF = 'candybrush_users_profiles_id_proof';
    const SOCIAL_ACCOUNT_INTEGRATION = 'candybrush_users_profiles_social_account_integration';
    const CUSTOM_MESSAGE = 'candybrush_users_profiles_custom_message';
    const BIRTH_DATE = 'candybrush_users_profiles_birth_date';
    const SEX = 'candybrush_users_profiles_sex';


    protected $table = self::TABLE;

    protected $fillable = [self::NAME, self::MOBILE, self::ADDRESS, self::STATE, self::CITY, self::PIN, self::LANGUAGE_KNOWN, self::DESCRIPTION,
        self::IMAGE, self::ID_PROOF, self::SOCIAL_ACCOUNT_INTEGRATION, self::CUSTOM_MESSAGE, self::BIRTH_DATE, self::SEX];

    public function user()
    {
        return $this->belongsTo('App\User', 'id');
    }
}
