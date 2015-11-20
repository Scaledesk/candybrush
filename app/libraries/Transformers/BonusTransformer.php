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
            'description'=>$bonus[Bonus::DESCRIPTION]
            /*'name'=>$bonus[Bonus::NAME],*/
        ];
    }
    public function requestAdaptor(){
        $data=array();
        $process_data=Input::get('data','');
        $package_id=Input::get('package_id',NULL);
        if(is_null($package_id)){
            unset($package_id);
            unset($process_data);
            return NULL;
        }
        if($process_data&&is_array($process_data)){
            foreach ($process_data as $bonus) {
                array_push($data, [
                    Bonus::NAME => $bonus['name'],
                    Bonus::DESCRIPTION => $bonus['description'],
                ]);
            }
            unset($process_data);
            $data['package_id']=$package_id;
            unset($package_id);
            return $data;
        }
        else{
            unset($process_data);
            unset($package_id);
            return NULL;
        }
    }
}