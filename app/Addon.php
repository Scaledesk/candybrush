<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Addon extends Model
{
    protected $table=self::TABLE;
    protected $primaryKey=self::ID;
    protected $fillable=[self::NAME,self::DESCRIPTION,self::PACKAGE_ID,self::DAYS,self::AMOUNT,self::TERMS];
    public $timestamps=false;

    // define constants
    const TABLE = 'candybrush_addons';
    const ID = 'candybrush_addons_id';
    const NAME='candybrush_addons_name';
    const DESCRIPTION = 'candybrush_addons_description';
    const PACKAGE_ID ='candybrush_addons_package_id';
    const AMOUNT='candybrush_addons_amount';
    const DAYS='candybrush_addons_days';
    const TERMS='candybrush_addons_terms';

    public function package(){
        return $this->belongsTo('App\PackagesModel','candybrush_addons_package_id');
    }

    /**
     * relation with Booking_packages_addons
     * has many as many buyers can book same package with same addons leads to many records
     * in corresponding table
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookingPackagesAddons(){
        return $this->hasMany('App\Booking_Packages_Addons','candybrush_bookings_addons_addon_id');
    }
}
