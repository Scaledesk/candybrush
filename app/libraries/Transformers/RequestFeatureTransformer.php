<?php
/**
 * Created by PhpStorm.
 * User: tushar
 * Date: 20/10/15
 * Time: 4:42 PM
 */

namespace app\libraries\Transformers;


use App\RequestFeature;
use App\User;
use Illuminate\Support\Facades\Input;
use League\Fractal\TransformerAbstract;
use App\libraries\Transformers\UserTransformer;
use App\libraries\Transformers\CategoryTransformer;
use App\libraries\Transformers\TagTransformer;

class RequestFeatureTransformer extends TransformerAbstract{
    protected $availableIncludes=['PostedBy','Tags','Category'];
    public function transform(RequestFeature $requestFeature){
     return [
         'id'=>$requestFeature[RequestFeature::ID],
         'title'=>$requestFeature[RequestFeature::TITLE],
         'description'=>$requestFeature[RequestFeature::DESCRIPTION],
         'budget'=>$requestFeature[RequestFeature::BUDGET],
     ];
    }
    public function requestAdaptor(){
        return[
            RequestFeature::TITLE=>Input::get('title',''),
            RequestFeature::DESCRIPTION=>Input::get('description',''),
            RequestFeature::BUDGET=>Input::get('budget',''),
            RequestFeature::CATEGORY_ID=>Input::get('category_id',''),
            RequestFeature::TAG_ID=>Input::get('tags_id',''),
            RequestFeature::USER_ID=>Input::get('user_id','')
        ];
    }
    public function includePostedBy(RequestFeature $requestFeature){
        return $this->item($requestFeature->postedBy()->first(),new UserTransformer());
    }
    public function includeCategory(RequestFeature $requestFeature){
        return $this->item($requestFeature->category()->first(),new CategoryTransformer());
    }
    public function includeTags(RequestFeature $requestFeature){
        return $this->collection($requestFeature->tags()->get(),new TagTransformer());
    }
}