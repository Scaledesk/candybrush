<?php
/**
 * Created by PhpStorm.
 * User: Javed
 * Date: 7/10/15
 * Time: 7:23 PM
 */
namespace App\libraries\Transformers;
use App\MessagesModel;
use App\MessagesUserModel;
use Dingo\Api\Http\Request;
use Illuminate\Support\Facades\Input;
use League\Fractal\TransformerAbstract;
class MessageTransformer extends TransformerAbstract{

    public function transform(MessagesUserModel $message){
        return [
            'subject'=>$message->candybrush_messages_subject,
            'body'=>$message->candybrush_messages_body,
            'status'=>$message->candybrush_messages_status
            /*,'user'=>self::includeUser($message)*/];
    }
    public function requestAdapter()
    {
        return [
            MessagesModel::SUBJECT => Input::get('subject'),
            MessagesModel::BODY => Input::get('body'),
            MessagesModel::STATUS => 0
        ];
    }
    public function includeUser(MessagesModel $message)
    {
        $user = $message->user;

        return $this->item($user, new UserTransformer());
    }
}