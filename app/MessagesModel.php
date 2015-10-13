<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MessagesModel extends Model
{
    // define constants

    const TABLE = 'candybrush_messages';
    const SUBJECT = 'candybrush_messages_subject';
    const BODY = 'candybrush_messages_body';
    const STATUS = 'candybrush_messages_status';

    protected $table=self::TABLE;
    protected $fillable=[self::SUBJECT, self::BODY, self::STATUS];
    public $timestamps=false;

    public function messagesUserModel()
    {
        return $this->hasMany('App\MessagesUserModel', 'candybrush_messages_message_id');
    }


}
