<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PackagesModel extends Model
{
    /**
     *  define constant
     */

    const TABLE = 'candybrush_packages';
    const NAME = 'candybrush_packages_name';
    const DESCRIPTION = 'candybrush_packages_description';
    const CATEGORY = 'candybrush_packages_category';
    const SUB_CATEGORY = 'candybrush_packages_sub_category';
    const PRICE = 'candybrush_packages_price';
    const BONUS = 'candybrush_packages_bonus';
    const OFFER = 'candybrush_packages_offer';
    const DEAL_PRICE = 'candybrush_packages_deal_price';
    const AVAILABLE_DATE = 'candybrush_packages_available_date';
    const TERM_CONDITION = 'candybrush_packages_term_condition';
    const PAYMENT_TYPE = 'candybrush_packages_payment_type';
    const MAXIMUM_DELIVERY_DAYS = 'candybrush_packages_maximum_delivery_days';
    protected $table = self::TABLE;
    public $timestamps = false;
    protected $fillable = [self::NAME, self::DESCRIPTION, self::CATEGORY, self::SUB_CATEGORY,
        self::PRICE, self::BONUS, self::OFFER, self::DEAL_PRICE, self::AVAILABLE_DATE, self::TERM_CONDITION,
        self::PAYMENT_TYPE, self::MAXIMUM_DELIVERY_DAYS];

    public function userPackages(){
        return $this->hasOne('App\PackegesUserModel','candybrush_users_packages_package_id');
    }

    public function addons(){
        return $this->hasMany('App\Addon','candybrush_addons_package_id');
    }
    public function bonus(){
        return $this->hasMany('App\Bonus','candybrush_bonus_package_id');
    }


}
