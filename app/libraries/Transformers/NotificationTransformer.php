<?php
/**
 * Created by PhpStorm.
 * User: tushar
 * Date: 29/10/15
 * Time: 2:43 PM
 */

namespace app\libraries\Transformers;


use App\Notification;
use Illuminate\Support\Facades\Input;
use League\Fractal\TransformerAbstract;
use App\libraries\Transformers\UserTransformer;

class NotificationTransformer extends TransformerAbstract{
    protected $defaultIncludes=[
        'User'
    ];
    public function transform(Notification $notification){
        return [
            'id'=>$notification[Notification::ID],
            'type'=>$notification[Notification::TYPE],
            'text'=>$notification[Notification::TEXT],
            'seen'=>$notification[Notification::SEEN]
        ];
    }
    public function requestAdaptor(){
        return [
          Notification::TYPE=>Input::get('type',''),
            Notification::TEXT=>Input::get('text',''),
            Notification::SEEN=>Input::get('seen',false),
            /*Notification::USER_ID=>Input::get('user_id',''),*/
        ];
    }
    public function includeUser(Notification $notification){
        return $this->item($notification->user()->first(),new UserTransformer());
    }
}