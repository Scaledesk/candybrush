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

class BadgeTransformer extends TransformerAbstract{
    public function transform(Badge $badge){
        return [
            Badge::ID=>$badge[Badge::ID],
            Badge::NAME=>$badge[Badge::NAME],
            Badge::IMAGE_URL=>$badge[Badge::IMAGE_URL],
        ];
    }
    public function requestAdaptor(){
        return [
            Badge::NAME=>Input::get('name',''),
            Badge::IMAGE_URL=>Input::get('image_url',''),
        ];
    }
}