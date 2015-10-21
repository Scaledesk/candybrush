<?php
/**
 * Created by PhpStorm.
 * User: tushar
 * Date: 10/10/15
 * Time: 5:33 PM
 */

namespace app\libraries\Transformers;


use App\Tag;
use Illuminate\Support\Facades\Input;
use League\Fractal\TransformerAbstract;
use App\libraries\Transformers\PackagesTransformer;
use App\libraries\Transformers\RequestFeatureTransformer;

class TagTransformer extends TransformerAbstract{
    protected $availableIncludes=['Packages','RequestFeatures'];
    public function transform($data){
        return [
            'id'=>$data[Tag::ID],
            'name'=>$data[Tag::NAME],
            'description'=>$data[Tag::DESCRIPTON]
        ];
    }
    public function requestAdaptor(){
        return [
            Tag::NAME => Input::get('name',''),
            Tag::DESCRIPTON => Input::get('description','')
        ];
    }
    public function includePackages(Tag $tag){
                return $this->collection($tag->packages()->get(),new PackagesTransformer());
    }
    public function includeRequestFeatures(Tag $tag){
        return $this->collection($tag->requestFeatures()->get(),new RequestFeatureTransformer());
    }
}