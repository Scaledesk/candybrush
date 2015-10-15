<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use SoftDeletes;
    //
    const ID = 'id';
    const TABLE = 'candybrush_messages';
    const SUBJECT = 'candybrush_messages_subject';
    const BODY = 'candybrush_messages_body';
    const STATUS = 'candybrush_messages_status';
    const USER_ID = 'candybrush_messages_user_id';
    const RECIEVER_ID = 'candybrush_messages_receivers_user_id';
    protected $table=self::TABLE;
    protected $fillable=[self::SUBJECT,self::BODY,self::STATUS,self::RECIEVER_ID];
    public $timestamps=true;
    protected $dates=['deleted_at'];

    public function user(){
        return $this->belongsTo('App\User','candybrush_messages_user_id');
    }
    public function recieverUsers(){
        return $this->belongsToMany('App\User', 'candybrush_messages_receivers','candybrush_messages_recievers_message_id','candybrush_messages_recievers_user_id')->withTimestamps();
    }
}
