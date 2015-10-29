<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table=self::TABLE;
    protected $primaryKey=self::ID;
    protected $fillable=[self::TYPE,self::TEXT,self::SEEN,self::USER_ID];
    public $timestamps=true;

    // define constants
    const TABLE = 'candybrush_notifications';
    const ID = 'candybrush_notifications_id';
    const TYPE='candybrush_notifications_type';
    const TEXT = 'candybrush_notifications_text';
    const SEEN='candybrush_notifications_seen';
    const USER_ID='candybrush_notifications_user_id';

    public static function types(){
        return
            [
                'MESSAGE',
                'WALLET_CREDIT',
                'WALLET_DEBIT',
                'NEW_GIG',
                'GIG_PUBLISHED'
            ];
    }
    public function user(){
     return $this->belongsTo('App\User','candybrush_notifications_user_id');
    }
}
