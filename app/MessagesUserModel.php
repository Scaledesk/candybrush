<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MessagesUserModel extends Model
{
    //

    const TABLE = 'candybrush_messages_user';
    const USER_ID = 'candybrush_messages_user_id';
    const MESSAGE_ID = 'candybrush_messages_message_id';
    const MESSAGE_TYPE = 'candybrush_messages_message_type';
    protected $table=self::TABLE;
    protected $fillable=[self::USER_ID, self::MESSAGE_ID, self::MESSAGE_TYPE];
    public $timestamps=false;

    public function messagesModel()
    {
        return $this->belongsTo('App\MessagesModel', 'candybrush_messages_message_id');
    }
    public function user()
    {
        return $this->belongsTo('App\User', 'candybrush_messages_user_id');
    }


}
