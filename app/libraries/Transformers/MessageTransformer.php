<?php
/**
 * Created by PhpStorm.
 * User: tushar
 * Date: 14/10/15
 * Time: 1:01 PM
 */

namespace app\libraries\Transformers;


use Illuminate\Support\Facades\Input;
use League\Fractal\TransformerAbstract;
use App\libraries\Transformers\UserTransformer;
use App\Message;

class MessageTransformer extends TransformerAbstract{
    protected $defaultIncludes=['User'];
    public function transform(Message $message){
        return [
            'id'=>$message[Message::ID],
            'subject'=>$message[Message::SUBJECT],
            'body'=>$message[Message::BODY],
            'read_status'=>$message[Message::STATUS]==0?'Unread':'read',
        ];
    }
    public function requestAdaptor(){
        return [
            Message::BODY=>Input::get('body',''),
            Message::SUBJECT=>Input::get('subject','nosubject'),
            Message::USER_ID=>Input::get('user_id',''),
            Message::RECIEVER_ID=>Input::get('receivers_id',''),
        ];
    }
    public function includeUser(Message $message){
        $user = $message->user;
        return $this->item($user, new UserTransformer());
    }
}