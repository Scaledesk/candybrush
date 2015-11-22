<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bookings_Packages_Bonus extends Model
{
    protected $table=self::TABLE;
    protected $primaryKey=self::ID;
    protected $fillable=[/*self::NAME,*/self::DESCRIPTION,self::PACKAGE_ID,self::BONUS_ID,self::BOOKING_ID];
    public $timestamps=false;

    // define constants
    const TABLE = 'candybrush_bookings_bonus';
    const ID = 'candybrush_bonus_id';
    const NAME='candybrush_bookings_bonus_name';
    const DESCRIPTION = 'candybrush_bookings_bonus_description';
    const PACKAGE_ID ='candybrush_bookings_bonus_package_id';
    const BONUS_ID="candybrush_bookings_bonus_bonus_id";
    const BOOKING_ID="candybrush_bookings_bonus_bookings_id";

    public function bonus(){
        return $this->belongsTo('App\Bonus',self::BONUS_ID);
    }
    public function booking(){
        return $this->belongsTo('App\Booking',self::BOOKING_ID);
    }
    public function package(){
        return $this->belongsTo('App\PackagesModel',self::PACKAGE_ID);
    }
}
