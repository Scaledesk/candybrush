<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //
    //
    protected $table=self::TABLE;
    protected $primaryKey=self::ID;
    protected $fillable=[self::NAME,self::PARENT_ID];
    public $timestamps=false;

    // define constants
    const TABLE = 'candybrush_categories';
    const ID = 'candybrush_categories_id';
    const NAME='candybrush_categories_name';
    const PARENT_ID = 'candybrush_categories_parent_id';
}
