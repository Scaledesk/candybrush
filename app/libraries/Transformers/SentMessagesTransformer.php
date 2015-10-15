<?php
/**
 * Created by PhpStorm.
 * User: tushar
 * Date: 15/10/15
 * Time: 1:34 PM
 */

namespace app\libraries\Transformers;


use App\Message;
use App\User;
use League\Fractal\TransformerAbstract;
use App\libraries\Transformers\UserTransformer;

class SentMessagesTransformer extends TransformerAbstract {
    protected $defaultIncludes=['User'];
public function transform(Message $message){
    return [
        'id'=>$message[Message::ID],
        'subject'=>$message[Message::SUBJECT],
        'body'=>$message[Message::BODY],
        ];
    }
    public function includeUser(Message $message){
        $users=$message->candybrush_messages_receivers_user_id;
        if($users!='draft'){
            $users=explode(',',$users);
          $user=User::whereIn('id',$users)->get();
            return $this->collection($user,new UserTransformer());
        }else{
        return;
        }


    }
}