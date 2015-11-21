<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking_Packages_Installments extends Model
{
    protected $table=self::TABLE;
    protected $primaryKey=self::ID;
    protected $fillable=[self::PACKAGE_ID,self::INSTALLMENT_NUMBER,self::INSTALLMENT_AMOUNT,self::ID,self::BOOKING_ID,self::INSTALLMENT_ID];
    public $timestamps=false;

    // define constants
    const TABLE = 'candybrush_bookings_packages_installments';
    const ID = 'candybrush_bookings_packages_installments_id';
    const PACKAGE_ID='candybrush_bookings_packages_installments_packages_id';
    const INSTALLMENT_ID="candybrush_bookings_packages_installments_installment_id";
    const INSTALLMENT_NUMBER = 'candybrush_bookings_packages_installments_installment_number';
    const INSTALLMENT_AMOUNT='candybrush_bookings_packages_installments_installment_amount';
    const BOOKING_ID="candybrush_bookings_packages_installments_bookings_id";

    public function installment(){
        return $this->belongsTo('App\Installment',self::INSTALLMENT_ID);
    }
    public function booking(){
        return $this->belongsTo('App\Booking','candybrush_bookings_packages_installments_bookings_id');
    }
    public function package(){
        return $this->belongsTo('App\PackagesModel','candybrush_bookings_packages_installments_packages_id');
    }
}
