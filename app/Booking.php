<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    /**
     * Defining constants
     */
    const TABLE = 'candybrush_bookings';
    const ID = 'candybrush_bookings_id';
    const USER_ID = 'candybrush_bookings_user_id';
    const PACKAGE_ID = 'candybrush_bookings_package_id';
    const PAYMENT_TYPE = 'candybrush_bookings_payment_type';
    const PAYMENT_STATUS = 'candybrush_bookings_payment_status';

    protected $table=self::TABLE;
    protected $fillable=[self::USER_ID,self::PACKAGE_ID,self::PAYMENT_TYPE,self::PAYMENT_STATUS];
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
}
