<?php
/**
 * Created by PhpStorm.
 * User: tushar
 * Date: 15/10/15
 * Time: 1:34 PM
 */

namespace app\libraries\Transformers;


use App\Message;
use League\Fractal\TransformerAbstract;
use App\libraries\Transformers\UserTransformer;

class SentMessagesTransformer extends TransformerAbstract {
    protected $defaultIncludes=['User'];
public function transform(Message $message){
    return [
        'id'=>$message[Message::ID],
        'subject'=>$message[Message::SUBJECT],
        'body'=>$message[Message::BODY],
        'read_status'=>$message[Message::STATUS]==0?'Unread':'read',
        ];
    }
    public function includeUser(Message $message){
        $user=$message->recieverUsers()->get();
        return $this->collection($user,new UserTransformer());
    }
}