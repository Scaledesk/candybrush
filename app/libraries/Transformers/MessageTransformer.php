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
use App\Message;

class MessageTransformer extends TransformerAbstract{
    public function transform(Message $message){
        return [
            'subject'=>$message[Message::SUBJECT]
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
}