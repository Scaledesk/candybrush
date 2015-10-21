<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    //
    protected $table=self::TABLE;
    protected $primaryKey=self::ID;
    protected $fillable=[self::NAME,self::DESCRIPTON];
    public $timestamps=false;

    // define constants
    const TABLE = 'candybrush_tags';
    const ID = 'candybrush_tags_id';
    const NAME='candybrush_tags_name';
    const DESCRIPTON = 'candybrush_tags_description';

    public function packages(){
            return $this->belongsToMany('App\PackagesModel','candybrush_packages_tags','candybrush_packages_tags_tag_id','candybrush_packages_tags_package_id');
    }

    public function requestFeatures(){
        return $this->belongsToMany('App\RequestFeature','candybrush_request_features_tags','candybrush_request_features_tags_tag_id','candybrush_request_features_tags_request_feature_id')->withTimestamps();
    }

}
