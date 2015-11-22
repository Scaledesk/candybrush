<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    /**
     * Defining constants
     */
    //with validations
    const TABLE = 'candybrush_bookings';
    const ID = 'candybrush_bookings_id';
    const BUYER_ID = 'candybrush_bookings_buyer_id';  //buyer id
    const PACKAGE_ID = 'candybrush_bookings_package_id';
    const SELLER_ID='candybrush_bookings_seller_id';
    const PRICE='candybrush_bookings_price';
    const DEAL_PRICE='candybrush_bookings_deal_price';

    //validation will be implemented
    const PACKAGE_DURATION='candybrush_bookings_duration';
    const PACKAGE_TITLE="candybrush_bookings_package_title";
    const PACKAGE_DESCRIPTION="candybrush_bookings_package_description";
    const PACKAGE_CATEGORY_ID="candybrush_bookings_package_category_id";
    const PACKAGE_AVERAGE_RATING="candybrush_bookings_package_average_rating";
    const PACKAGE_AVILABLE_DATES="candybrush_bookings_package_available_dates";
    const PACKAGE_TERM_CONDITION="candybrush_bookings_package_term_condition";
    const PAYMENT_TYPE = 'candybrush_bookings_payment_type';
    const PACKAGE_DELIVERY_TIME="candybrush_bookings_package_delivery_time";
    const PACKAGE_DELIVERY_TIME_TYPE="candybrush_bookings_package_delivery_time_type";
    const PACKAGE_INSTRUCTIONS="candybrush_bookings_package_instructions";
    const PACKAGE_LOCATION="candybrush_bookings_package_location";
    const PACKAGE_MEETING_AVAILABILITY="candybrush_bookings_package_meeting_availability";
    const PACKAGE_MEETING_ADDRESS="candybrush_bookings_package_meeting_address";
    const PACKAGE_DELIVERY_TYPE_ID="candybrush_bookings_package_delivery_type_id";
    const PACKAGE_DELIVERY_TYPE_NAME="candybrush_bookings_package_delivery_type_name";
    const PACKAGE_PAYMENT_TYPE_ID="candybrush_bookings_package_payment_type_id";
    const PACKAGE_PAYMENT_TYPE_NAME="candybrush_bookings_package_payment_type_name";
    const PACKAGE_TYPE_ID="candybrush_bookings_package_type_id";
    const PACKAGE_TYPE_NAME="candybrush_bookings_package_type_name";
    const PAYMENT_STATUS = 'candybrush_bookings_payment_status';
    const BOOKING_STATUS="candybrush_bookings_status";
    const PACKAGE_TIMESTAMP = "candybrush_bookings_packages_timestamp";
    protected $table=self::TABLE;
    protected $fillable=[
        self::BUYER_ID,self::PACKAGE_ID,self::SELLER_ID,self::PRICE,self::DEAL_PRICE,
        self::PACKAGE_DURATION,self::PACKAGE_TITLE,self::PACKAGE_DESCRIPTION,self::PACKAGE_CATEGORY_ID,
        self::PACKAGE_AVERAGE_RATING,self::PACKAGE_AVILABLE_DATES,self::PACKAGE_TERM_CONDITION,self::PAYMENT_TYPE,
        self::PACKAGE_DELIVERY_TIME,self::PACKAGE_DELIVERY_TIME_TYPE,self::PACKAGE_INSTRUCTIONS,self::PACKAGE_LOCATION,
        self::PACKAGE_MEETING_AVAILABILITY,self::PACKAGE_MEETING_ADDRESS,self::PACKAGE_DELIVERY_TYPE_ID,self::PACKAGE_DELIVERY_TYPE_NAME,
        self::PACKAGE_PAYMENT_TYPE_ID,self::PACKAGE_PAYMENT_TYPE_NAME,self::PACKAGE_TYPE_ID,self::PACKAGE_TYPE_NAME,
        self::PAYMENT_STATUS,self::BOOKING_STATUS
    ];
    public $timestamps=true;
    protected $primaryKey=self::ID;

    public function buyer(){
    // who buy the package
    return $this->belongsTo('App\User','candybrush_bookings_user_id');
    }
    public function package(){
        //package sold
        return $this->belongsTo('App\PackagesModel','candybrush_bookings_package_id');
    }

    /**
     * retation with Booking packages tags
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookingPackagesTags(){
        return $this->hasMany('App\Bookings_Package_Tags','candybrush_bookings_packages_tags_bookings_id');
    }
}
