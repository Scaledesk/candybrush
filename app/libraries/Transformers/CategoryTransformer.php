<?php
/**
 * Created by PhpStorm.
 * User: tushar
 * Date: 10/10/15
 * Time: 5:33 PM
 */

namespace app\libraries\Transformers;


use App\Category;
use App\libraries\Transformers\PackagesTransformer;
use App\libraries\Transformers\RequestFeatureTransformer;
use Illuminate\Support\Facades\Input;
use League\Fractal\TransformerAbstract;


class CategoryTransformer extends TransformerAbstract{
    protected $availableIncludes=['Packages','RequestFeature'];
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
    public function includePackages(Category $category){
        return $this->collection($category->packages()->get(),new PackagesTransformer());
    }
    public function includeRequestFeature(Category $category){
        return $this->collection($category->requestFeature()->get(),new RequestFeatureTransformer());
    }
}