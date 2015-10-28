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

    public function users(){
        return $this->belongsToMany('App\User','candybrush_badges_users','candybrush_badges_users_badge_id','candybrush_badges_users_users_id');
    }
    public function usersById($user_id){
        return $this->belongsToMany('App\User','candybrush_badges_users','candybrush_badges_users_badge_id','candybrush_badges_users_users_id')->where('candybrush_badges_users_users_id',$user_id);
    }
}
