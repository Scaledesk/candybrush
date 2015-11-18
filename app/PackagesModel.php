<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;
/*use Illuminate\Support\ServiceProvider as Provider;
use Sofa\Eloquence\ServiceProvider as Eloquence;*/

class PackagesModel extends Model
{
    use SearchableTrait;
    protected $primaryKey=PackagesModel::ID;
//    protected $test;


    /**
     *  define constant
     */
    /**
     * Packages Status
     */
    const ID='id';
    const ACTIVE ='ACTIVE';
    const PENDING_APPROVAL ='PENDING_APPROVAL';
    const REQUIRES_MODIFICATION ='REQUIRES_MODIFICATION';
    const DENIED ='DENIED';
    const PAUSED ='PAUSED';

    /**
     * table constants
     */
    const TABLE = 'candybrush_packages';
    const NAME = 'candybrush_packages_name';
    const DESCRIPTION = 'candybrush_packages_description';
    const CATEGORY_ID = 'candybrush_packages_category_id';
    const TAG_ID='candybrush_packages_tag_id';
    /*const SUB_CATEGORY = 'candybrush_packages_sub_category';*/
    const PRICE = 'candybrush_packages_price';
    const DEAL_PRICE = 'candybrush_packages_deal_price';
    const AVAILABLE_DATE = 'candybrush_packages_available_dates';
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

    protected $searchable=[
        'columns'=>[
            'candybrush_packages_name'=>10,
            'candybrush_packages_description'=>9,
            'candybrush_categories.candybrush_categories_name'=>8,
            'candybrush_tags.candybrush_tags_name'=>7,
            'candybrush_tags.candybrush_tags_description'=>6,
            'users.email'=>4,
            'candybrush_bonus.candybrush_bonus_name'=>3,
            'candybrush_bonus.candybrush_bonus_description'=>2,
            'candybrush_addons.candybrush_addons_name'=>1,
            'candybrush_addons.candybrush_addons_description'=>0,
            'candybrush_packages.candybrush_packages_payment_type'=>0
        ],
        'joins'=>[
            'candybrush_packages_tags'=>['candybrush_packages_tags.candybrush_packages_tags_package_id','candybrush_packages.id'],
            'candybrush_tags'=>['candybrush_tags.candybrush_tags_id','candybrush_packages_tags.candybrush_packages_tags_tag_id'],
            'candybrush_categories'=>['candybrush_categories.candybrush_categories_id','candybrush_packages.candybrush_packages_category_id'],
            'users'=>['users.id','candybrush_packages.candybrush_packages_user_id'],
            'candybrush_addons'=>['candybrush_packages.id','candybrush_addons.candybrush_addons_package_id'],
            'candybrush_bonus'=>['candybrush_packages.id','candybrush_bonus.candybrush_bonus_package_id']
        ]
    ];

    /*protected $searchableColumns = ['candybrush_packages_name',
        'candybrush_packages_description',
        'candybrush_categories.candybrush_categories_name',
        'candybrush_tags.candybrush_tags_name',
        'candybrush_tags.candybrush_tags_description',
        'users.name',
        'users.email',
        'candybrush_bonus.candybrush_bonus_name',
        'candybrush_bonus.candybrush_bonus_description',
        'candybrush_addons.candybrush_addons_name',
        'candybrush_addons.candybrush_addons_description',
        'candybrush_packages.candybrush_packages_payment_type'];*/

    /*function __construct()
    {

        $test = new Eloquence('Sofa\Eloquence\ServiceProvider');
        $test->boot();
    }*/

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
    public function bookings(){
        return $this->hasMany('App\Booking','candybrush_bookings_package_id');
    }
    public function photos(){
        return $this->hasMany('App\PackagePhoto',PackagePhoto::PACKAGE_ID);
    }
}
