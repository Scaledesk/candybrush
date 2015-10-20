<?php

namespace App\Http\Controllers;

use App\Category;
use App\libraries\Transformers\RequestFeatureTransformer;
use App\RequestFeature;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Mockery\CountValidator\Exception;

class RequestFeatureController extends BaseController
{
    protected $request_feature_transformer;

    function __construct()
    {
        $this->request_feature_transformer=new RequestFeatureTransformer();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->response()->collection(RequestFeature::all(),$this->request_feature_transformer);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data=$this->request_feature_transformer->requestAdaptor();
        $validation_result=$this->my_validate([
             'data'=>$data,
            'rules'=>[
                RequestFeature::USER_ID=>'required|exists:users,id|numeric',
                RequestFeature::CATEGORY_ID=>'required|min:1|exists:candybrush_categories,candybrush_categories_id',
                RequestFeature::BUDGET=>'required|numeric',
                RequestFeature::DESCRIPTION=>'required|min:20',
                RequestFeature::TITLE=>'required'
            ],
            'messages'=>[
                RequestFeature::USER_ID.'.required'=>'User_id is require try user_id=<user_id>',
                RequestFeature::USER_ID.'.exists'=>'user_id do not match any records, please check',
                RequestFeature::USER_ID.'.numeric'=>'Only numbers are allowed as user_id, please check',
                RequestFeature::CATEGORY_ID.'.required'=>'Category_id is required try category_id=<category_id>',
                RequestFeature::CATEGORY_ID.'.min'=>'At least 1 category id has to be given in array',
                RequestFeature::CATEGORY_ID.'.exists'=>'Category _id do not match any records, please check',
                RequestFeature::BUDGET.'.required'=>'Budget is required try budget=<your budget>',
                RequestFeature::BUDGET.'.numeric'=>'Only numbers are allowed in Budget field',
                RequestFeature::DESCRIPTION.'.required'=>'Description is required try description=<description>',
                RequestFeature::DESCRIPTION.'.min'=>'min 20 characters are necessary in description',
                RequestFeature::TITLE.'.required'=>'Title is required try title=<title>'
            ]
            ]);
        if($validation_result['result']){
            try{
                $tag_avilable=FALSE;
                $tags_id=NULL;
                if($data[RequestFeature::TAG_ID]){
                    $tags_id=explode(',',$data[RequestFeature::TAG_ID]);
                    unset($data[RequestFeature::TAG_ID]);
                    $tag_avilable=TRUE;
                }
                $data=array_filter($data,'strlen');
                /*print_r($data);
                die;*/
                $result=  DB::transaction(function()use($data,$tag_avilable,$tags_id) {
                    $category=Category::find($data[RequestFeature::CATEGORY_ID]);
                    $require_feature = new RequestFeature($data);
                    $category->requestFeature()->save($require_feature);
                    if ($tag_avilable) {
                        $require_feature->tags()->attach($tags_id);
                    }
                    return $this->success();
                });
                return $result;
            }catch(\Exception $e){
               return $this->error('unknown error occurred!Might wrong tag id passed, please check',520);
            }
        }else{
            return $validation_result['error'];
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $request_feature=RequestFeature::where('candybrush_request_features_id',$id)->first();
        if(is_null($request_feature)){return $this->error('Request feature id do not match any records, try again');}
        return $this->response()->item($request_feature,$this->request_feature_transformer);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $request_feature=RequestFeature::where('candybrush_request_features_id',$id)->first();
        if(is_null($request_feature)){return $this->error('Request feature id do not match any records, try again');}
        $result=DB::transaction(function()use($request_feature){
            try{$request_feature->delete();
                return $this->success();}catch(\Exception $e){
                return $this->error('some unknown error occurred, try again',520);
            }
        });
        return $result;
    }
}
