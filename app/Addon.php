<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Addon extends Model
{
    protected $table=self::TABLE;
    protected $primaryKey=self::ID;
    protected $fillable=[self::NAME,self::DESCRIPTION,self::PACKAGE_ID,self::DAYS,self::PRICE];
    public $timestamps=false;

    // define constants
    const TABLE = 'candybrush_addons';
    const ID = 'candybrush_addons_id';
    const NAME='candybrush_addons_name';
    const DESCRIPTION = 'candybrush_addons_description';
    const PACKAGE_ID ='candybrush_addons_package_id';
    const PRICE='candybrush_addons_price';
    const DAYS='candybrush_addons_days';

    public function package(){
        return $this->belongsTo('App\PackagesModel','candybrush_addons_package_id');
    }
}
