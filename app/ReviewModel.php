<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReviewModel extends Model
{
    // define constants
    const TABLE = 'candybrush_reviews';
    const USER_ID = 'candybrush_reviews_user_id';
    const PACKAGE_ID = 'candybrush_reviews_package_id';
    const RATING = 'candybrush_reviews_rating';
    const COMMENT = 'candybrush_reviews_comment';
    const ADMIN_VERIFIED = 'candybrush_reviews_admin_verified';

    protected $table=self::TABLE;
    protected $fillable=[self::USER_ID, self::PACKAGE_ID, self::RATING, self::COMMENT, self::ADMIN_VERIFIED];
    public $timestamps=false;


    /*
     * relation with user
     */

    public function package(){
        $this->belongsTo('App\PackagesModel','candybrush_reviews_package_id');
    }

    public function user(){
        $this->belongsTo('App\User','candybrush_reviews_user_id');
    }

}
