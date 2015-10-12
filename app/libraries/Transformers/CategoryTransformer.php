<?php
/**
 * Created by PhpStorm.
 * User: tushar
 * Date: 10/10/15
 * Time: 5:33 PM
 */

namespace app\libraries\Transformers;


use App\Category;
use Illuminate\Support\Facades\Input;
use League\Fractal\TransformerAbstract;

class CategoryTransformer extends TransformerAbstract{

    public function transform($data){
        return [
            'id'=>$data[Category::ID],
            'name'=>$data[Category::NAME],
            'parent_id'=>$data[Category::PARENT_ID]
        ];
    }
    public function requestAdaptor(){
        return [
            Category::NAME => Input::get('name',''),
            Category::PARENT_ID => Input::get('parent_id','')
        ];
    }
}