<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class PackagePhoto extends Model
{
    protected $table=self::TABLE;
    protected $primaryKey=self::ID;
    protected $fillable=[self::URL,self::PACKAGE_ID];
    public $timestamps=false;

    // define constants
    const TABLE = 'candybrush_packages_photos';
    const ID = 'candybrush_packages_photos_id';
    const URL='candybrush_packages_photos_url';
    const PACKAGE_ID ='candybrush_packages_photos_packages_id';

    public function package(){
        return $this->belongsTo('App\PackagesModel',PackagePhoto::PACKAGE_ID);
    }
}
