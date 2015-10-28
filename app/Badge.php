<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    protected $table=self::TABLE;
    protected $primaryKey=self::ID;
    protected $fillable=[self::NAME,self::IMAGE_URL];
    public $timestamps=false;

    // define constants
    const TABLE = 'candybrush_badges';
    const ID = 'candybrush_badges_id';
    const NAME='candybrush_badges_name';
    const IMAGE_URL = 'candybrush_badges_image_url';

    public function userProfiles(){
        //return userprofiles
    }
}
