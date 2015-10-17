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
    const CATEGORY_ID = 'candybrush_packages_category_id';
    const TAG_ID='candybrush_packages_tag_id';
    /*const SUB_CATEGORY = 'candybrush_packages_sub_category';*/
    const PRICE = 'candybrush_packages_price';
    const DEAL_PRICE = 'candybrush_packages_deal_price';
    const AVAILABLE_DATE = 'candybrush_packages_available_date';
    const TERM_CONDITION = 'candybrush_packages_term_condition';
    const PAYMENT_TYPE = 'candybrush_packages_payment_type';
    const MAXIMUM_DELIVERY_DAYS = 'candybrush_packages_maximum_delivery_days';
    const User_ID='candybrush_packages_user_id';
    const STATUS='candybrush_packages_status';
    protected $table = self::TABLE;
    public $timestamps = false;
    protected $fillable = [self::NAME, self::DESCRIPTION, self::CATEGORY_ID, self::TAG_ID,
        self::PRICE, self::DEAL_PRICE, self::AVAILABLE_DATE, self::TERM_CONDITION,
        self::PAYMENT_TYPE, self::MAXIMUM_DELIVERY_DAYS,self::User_ID,self::STATUS];

    public function seller(){
        return $this->belongsTo('App\User','candybrush_packages_user_id');
    }

    public function addons(){
        return $this->hasMany('App\Addon','candybrush_addons_package_id');
    }
    public function bonus(){
        return $this->hasMany('App\Bonus','candybrush_bonus_package_id');
    }
    public function category(){
        return $this->belongsTo('App\Category','candybrush_packages_category_id');
    }
    public function tags(){
        return $this->belongsToMany('App\Tag','candybrush_packages_tags','candybrush_packages_tags_package_id','candybrush_packages_tags_tag_id');
    }
}
