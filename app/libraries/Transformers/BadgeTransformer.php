<?php
/**
 * Created by PhpStorm.
 * User: tushar
 * Date: 28/10/15
 * Time: 1:34 PM
 */

namespace app\libraries\Transformers;


use App\Badge;
use Illuminate\Support\Facades\Input;
use League\Fractal\TransformerAbstract;
use App\libraries\Transformers\UserTransformer;

class BadgeTransformer extends TransformerAbstract{
    protected $availableIncludes=['Users'];
    public function transform(Badge $badge){
        return [
            'id'=>$badge[Badge::ID],
            'name'=>$badge[Badge::NAME],
            'image_url'=>$badge[Badge::IMAGE_URL],
        ];
    }
    public function requestAdaptor(){
        return [
            Badge::NAME=>Input::get('name',''),
            Badge::IMAGE_URL=>Input::get('image_url',''),
        ];
    }
    public function includeUsers(Badge $badge){
        return $this->collection($badge->users()->get(),new UserTransformer());
    }
}