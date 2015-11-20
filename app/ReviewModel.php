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
    const SELLER_COMMUNICATION_RATING='candybrush_reviews_seller_communication_rating';
    const SELLER_AS_DESCRIBED_RATING='candybrush_reviews_seller_as_described';
    const WOULD_RECONMMEND_RATING='candybrush_reviews_would_recommend';

    protected $table=self::TABLE;
    protected $fillable=[self::USER_ID, self::PACKAGE_ID, self::COMMENT, self::ADMIN_VERIFIED,self::SELLER_COMMUNICATION_RATING,self::WOULD_RECONMMEND_RATING,self::SELLER_AS_DESCRIBED_RATING];
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
