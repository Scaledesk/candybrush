<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    //
    const TABLE = 'candybrush_messages';
    const SUBJECT = 'candybrush_messages_subject';
    const BODY = 'candybrush_messages_body';
    const STATUS = 'candybrush_messages_status';
    const USER_ID = 'candybrush_messages_user_id';
    const RECIEVER_ID = 'candybrush_messages_receivers_user_id';
    protected $table=self::TABLE;
    protected $fillable=[self::SUBJECT,self::BODY,self::STATUS];
    public $timestamps=true;

    public function user(){
        return $this->belongsTo('App\User','candybrush_messages_user_id');
    }
    public function recieverUsers(){
        return $this->belongsToMany('App\User', 'candybrush_messages_receivers','candybrush_messages_recievers_message_id','candybrush_messages_recievers_user_id');
    }
}
