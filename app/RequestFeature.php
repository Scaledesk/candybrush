<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequestFeature extends Model
{
    const TABLE = 'candybrush_request_features';
    const ID = 'candybrush_request_features_id';
    const TITLE = 'candybrush_request_features_title';
    const USER_ID = 'candybrush_request_features_user_id';
    const DESCRIPTION = 'candybrush_request_features_description';
    const BUDGET = 'candybrush_request_features_budget';
    const CATEGORY_ID='candybrush_request_features_category_id';
    const TAG_ID='candybrush_request_features_tag_id';

    protected $table=self::TABLE;
    protected $fillable=[self::USER_ID,self::TABLE,self::DESCRIPTION,self::BUDGET,self::CATEGORY_ID,self::TAG_ID,self::TITLE];
    public $timestamps=false;
    protected $primaryKey=self::ID;

    public function postedBy(){
        return $this->belongsTo('App\User','candybrush_request_features_user_id');
    }

    public function category(){
        return $this->belongsTo('App\Category','candybrush_request_features_category_id');
    }

    public function tags(){
        return $this->belongsToMany('App\Tag','candybrush_request_features_tags','candybrush_request_features_tags_request_feature_id','candybrush_request_features_tags_tag_id')->withTimestamps();
    }

}
