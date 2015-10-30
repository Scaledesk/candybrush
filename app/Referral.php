<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    protected $table=self::TABLE;
    protected $primaryKey=self::ID;
    protected $fillable=[self::USER_ID,self::REFFERED_USER_ID,self::REFFERED_USER_EMAIL];
    public $timestamps=false;

    // define constants
    const TABLE = 'candybrush_referrals';
    const ID = 'candybrush_referrals_id';
    const USER_ID='candybrush_referrals_users_id';
    const REFFERED_USER_ID = 'candybrush_referrals_referred_users_id';
    const REFFERED_USER_EMAIL ='candybrush_referrals_referred_users_email';
    const REFERRAL_CODE ='candybrush_referrals_referral_code';
}
