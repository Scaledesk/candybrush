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
    const INSTRUCTIONS='candybrush_packages_instructions';
    const LOCATION = 'candybrush_packages_location';
    const MEETING_AVAILABILITY="candybrush_packages_meeting_availability";
    const MEETING_ADDRESS="candybrush_packages_meeting_address";
    const DELIVERY_TIME="candybrush_packages_delivery_time";
    const DELIVERY_TIME_TYPE="candybrush_packages_delivery_time_type";
    const DELIVERY_TYPE_NAME="candybrush_packages_delivery_type_name";
    const DELIVERY_TYPE_ID="candybrush_packages_delivery_type_id";
    const PAYMENT_TYPE_ID="candybrush_packages_payment_type_id";
    const PAYMENT_TYPE_NAME="candybrush_packages_payment_type_name";
    const PACKAGE_TYPE_ID="candybrush_package_package_type_id";
    const PACKAGE_TYPE_NAME="candybrush_package_package_type_name";
    const PACKAGE_AVERAGE_RATING="candybrush_packages_average_rating";
    const PACKAGE_TIMESTAMP = "candybrush_packages_updated_at";
    const PAYMENT_TYPE_COD="candybrush_packages_payment_cod";
    const PAYMENT_TYPE_INS="candybrush_packages_payment_installment";
    const PAYMENT_TYPE_OT="candybrush_packages_payment_onetime";

    protected $table = self::TABLE;
    public $timestamps = true;
    protected $fillable = [self::NAME, self::DESCRIPTION, self::CATEGORY_ID, self::TAG_ID,
        self::PRICE, self::DEAL_PRICE, self::AVAILABLE_DATE, self::TERM_CONDITION,
        self::PAYMENT_TYPE, self::MAXIMUM_DELIVERY_DAYS,self::User_ID,self::STATUS,self::INSTRUCTIONS,self::LOCATION,self::MEETING_ADDRESS,self::MEETING_AVAILABILITY,self::DELIVERY_TIME_TYPE,self::DELIVERY_TIME,self::PAYMENT_TYPE_ID,self::PAYMENT_TYPE_NAME,self::PACKAGE_TYPE_ID,self::PACKAGE_TYPE_NAME,self::DELIVERY_TYPE_ID,self::DELIVERY_TYPE_NAME,self::PAYMENT_TYPE_COD,self::PAYMENT_TYPE_INS,self::PAYMENT_TYPE_OT];

    protected $searchable=[
        'columns'=>[
            'candybrush_packages_name'=>10,
            'candybrush_packages_description'=>9,
            'candybrush_packages_location'=>8,
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
    public function reviews(){
        return $this->hasMany('App\ReviewModel',ReviewModel::PACKAGE_ID);
    }
    public function installments(){
        return $this->hasMany('App\Installment','candybrush_packages_installments_packages_id');
    }
    /**
     * relation with booking packages addons
     * has many as in case of booking of
     * more than one addon of same package
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookingPackagesAddons(){
        return $this->hasMany('App\Booking_Packages_Addons','candybrush_bookings_addons_package_id');
    }
    /**
     * relation with BookingsPackagesBonus
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookingsPackagesBonus(){
        return $this->hasMany('App\Bookings_Packages_Bonus','candybrush_bookings_bonus_package_id');
    }
    /**
     * relation with Booking_Packages_Installments
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Booking_Packages_Installments(){
        return $this->hasMany('App\Booking_Packages_Installments','candybrush_bookings_packages_installments_packages_id');
    }
}
