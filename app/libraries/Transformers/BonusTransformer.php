<?php
/**
 * Created by PhpStorm.
 * User: tushar
 * Date: 17/10/15
 * Time: 11:31 AM
 */

namespace app\libraries\Transformers;


use App\Bonus;
use Illuminate\Support\Facades\Input;
use League\Fractal\TransformerAbstract;

class BonusTransformer extends TransformerAbstract{
    public function transform(Bonus $bonus){
        return [
            'id'=>$bonus[Bonus::ID],
            'description'=>$bonus[Bonus::DESCRIPTION],
            'name'=>$bonus[Bonus::NAME]
        ];
    }
    public function requestAdaptor(){
        return [
            Bonus::PACKAGE_ID=>Input::get('package_id',''),
            Bonus::NAME=>Input::get('name',''),
            Bonus::DESCRIPTION=>Input::get('description','')
        ];
    }
}