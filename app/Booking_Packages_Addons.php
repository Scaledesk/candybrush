<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking_Packages_Addons extends Model
{
    protected $table=self::TABLE;
    protected $primaryKey=self::ID;
    protected $fillable=[self::NAME,self::DESCRIPTION,self::PACKAGE_ID,self::DAYS,self::AMOUNT,self::TERMS,self::BOOKING_ID];
    public $timestamps=false;

    // define constants
    const TABLE = 'candybrush_bookings_addons';
    const ID = 'candybrush_bookings_addons_id';
    const NAME='candybrush_bookings_addons_name';
    const DESCRIPTION = 'candybrush_bookings_addons_description';
    const PACKAGE_ID ='candybrush_bookings_addons_package_id';
    const AMOUNT='candybrush_bookings_addons_amount';
    const DAYS='candybrush_bookings_addons_days';
    const TERMS='candybrush_bookings_addons_terms';
    const BOOKING_ID='candybrush_bookings_addons_bookings_id';

    public function addon(){
        return $this->belongsTo('App\Addon','candybrush_bookings_addons_id');
    }
    public function booking(){
        return $this->belongsTo('App\Booking','candybrush_bookings_addons_bookings_id');
    }
    public function package(){
        return $this->belongsTo('App\PackagesModel','candybrush_bookings_addons_package_id');
    }
}
