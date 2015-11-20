<?php
/**
 * Created by PhpStorm.
 * User: tushar
 * Date: 16/10/15
 * Time: 3:23 PM
 */

namespace app\libraries\Transformers;


use App\Addon;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Input;
use League\Fractal\TransformerAbstract;

class AddonTransformer extends TransformerAbstract{
    public function transform(Addon $addon){
        return [
            'id'=>$addon[Addon::ID],
            'description'=>$addon[Addon::DESCRIPTION],
            'name'=>$addon[Addon::NAME],
            'amount'=>(integer)$addon[Addon::AMOUNT],
            'days'=>(integer)$addon[Addon::DAYS]
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
            foreach ($process_data as $addon) {
                array_push($data, [
                    Addon::NAME => $addon['name'],
                    Addon::DESCRIPTION => $addon['description'],
                    Addon::DAYS => $addon['days'],
                    Addon::AMOUNT => $addon['amount']
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