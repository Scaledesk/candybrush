<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bonus extends Model
{
    protected $table=self::TABLE;
    protected $primaryKey=self::ID;
    protected $fillable=[/*self::NAME,*/self::DESCRIPTION,self::PACKAGE_ID];
    public $timestamps=false;

    // define constants
    const TABLE = 'candybrush_bonus';
    const ID = 'candybrush_bonus_id';
    const NAME='candybrush_bonus_name';
    const DESCRIPTION = 'candybrush_bonus_description';
    const PACKAGE_ID ='candybrush_bonus_package_id';

    public function package(){
        return $this->belongsTo('App\PackagesModel','candybrush_bonus_package_id');
    }

    /**
     * relation with bookings packages bonus
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookingsPackagesBonus(){
        return $this->hasMany('App\Bookings_Packages_Bonus',Bookings_Packages_Bonus::BONUS_ID);
    }
}
