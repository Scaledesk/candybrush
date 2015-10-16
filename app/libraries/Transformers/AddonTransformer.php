<?php
/**
 * Created by PhpStorm.
 * User: tushar
 * Date: 16/10/15
 * Time: 3:23 PM
 */

namespace app\libraries\Transformers;


use App\Addon;
use Illuminate\Support\Facades\Input;
use League\Fractal\TransformerAbstract;

class AddonTransformer extends TransformerAbstract{
    public function transform(Addon $addon){
        return [
            'id'=>$addon[Addon::ID],
            'description'=>$addon[Addon::DESCRIPTION],
            'name'=>$addon[Addon::NAME],
            'price'=>(integer)$addon[Addon::PRICE],
            'days'=>(integer)$addon[Addon::DAYS]
        ];
    }
    public function requestAdaptor(){
        return [
            Addon::PACKAGE_ID=>Input::get('package_id',''),
            Addon::NAME=>Input::get('name',''),
            Addon::DESCRIPTION=>Input::get('description',''),
            Addon::DAYS=>Input::get('days',''),
            Addon::PRICE=>Input::get('price','')
        ];
    }
}