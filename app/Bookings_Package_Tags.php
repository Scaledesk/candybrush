<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bookings_Package_Tags extends Model
{
    //
    protected $table=self::TABLE;
    protected $primaryKey=self::ID;
    protected $fillable=[self::BOOKING_ID,self::TAG_ID];
    public $timestamps=false;

    // define constants
    const TABLE = 'candybrush_bookings_packages_tags';
    const ID = 'candybrush_bookings_packages_tags_id';
    const TAG_ID='candybrush_bookings_packages_tags_tag_id';
    const BOOKING_ID = 'candybrush_bookings_packages_tags_bookings_id';

    public function booking(){
        return $this->belongsTo('App\Booking','candybrush_bookings_packages_tags_bookings_id');
    }

    public function tag(){
        return $this->belongsTo('App\Tag','candybrush_bookings_packages_tags_tag_id');
    }
}
