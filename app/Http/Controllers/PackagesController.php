<?php

namespace App\Http\Controllers;

use App\Category;
use App\libraries\Transformers\PackagesTransformer;
use App\PackegesUserModel;
use App\Tag;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\PackagesModel;
use App\libraries\Constants;
use Illuminate\Support\Facades\DB;
use Mockery\CountValidator\Exception;

class PackagesController extends BaseController
{
    /**
     * creating constructor
     */
    protected $packageTransformer;
    function __construct(PackagesTransformer $packageTransformer)
    {
        $this->packageTransformer = $packageTransformer;
        // $this->middleware('jwt.auth',['except'=>['authenticate']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    return $this->response()->collection(PackagesModel::all(),$this->packageTransformer);
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
        $u_id = $request->user_id;
        $data = $this->packageTransformer->requestAdapter();
        $data=array_filter($data,'strlen'); // filter blank or null array
        $validation_result=$this->my_validate([
            'data'=>$data,
            'rules'=>[
                PackagesModel::NAME=>'required',
                PackagesModel::DESCRIPTION=>'required',
                PackagesModel::User_ID=>'required|exists:users,id',
                PackagesModel::CATEGORY_ID=>'required|min:1|exists:candybrush_categories,candybrush_categories_id'
            ],
            'messages'=>[
                PackagesModel::NAME.'.required'=>'Name of package is required, try name=<name>',
                PackagesModel::DESCRIPTION.'.required|Name of package is required, try description=<description>',
                PackagesModel::User_ID.'.required'=>'seller_id is required try user_id=<user_id>',
                PackagesModel::User_ID.'.exists'=>'seller_id do not match any records, please check',
                PackagesModel::CATEGORY_ID.'.required'=>'Category_id is required try category_id=<array od category_ids>',
                PackagesModel::CATEGORY_ID.'.min'=>'At least 1 category id has to be given in array',
                PackagesModel::CATEGORY_ID.'.exists'=>'Category _id do not match any records, please check',
            ]

        ]);
        if($validation_result['result']){
            try{
            $tag_avilable=false;
            $tags_id=NULL;
            if(isset($data[PackagesModel::TAG_ID])){
            $tags_id=explode(',',$data[PackagesModel::TAG_ID]);
                unset($data[PackagesModel::TAG_ID]);
                $tag_avilable=true;
            }
            DB::transaction(function ()use($data,$tag_avilable,$tags_id) {
                $category=Category::find($data[PackagesModel::CATEGORY_ID]);
                $package = new PackagesModel($data);
                $category->packages()->save($package);
                if ($tag_avilable) {

                    $package->tags()->attach($tags_id);
                }
            });
            return $this->success();}catch(Exception $e){
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
        //
        $data=['id'=>$id];
        $validation_result=$this->my_validate(['data'=>$data,
            'rules'=>[
                'id'=>'required|exists:'.Constants::PREFIX.'packages,id'
            ],
            'messages'=>[
                'id.required'=>'package id is required to show package try in url package/<id>',
                'id.exists'=>'package id do not match any records, it not exists or already deleted'
            ]
        ]);
        if($validation_result['result']){
           return $this->response()->item(PackagesModel::find($id),$this->packageTransformer);
        }else{
            return $validation_result['error'];
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {
        $data = $this->packageTransformer->requestAdapter();
        dd($data);
        $data=array_filter($data,'strlen'); // filter blank or null array
        if(sizeof($data)){ try{$result=PackagesModel::where('id', $id)->update($data);}catch(\Exception $e){
            return $this->error($e->getMessage(),$e->getCode());
        }
        }else{
            return $this->error('no adequate field passed',422);
        }
        if($result)
        {
            return $this->success();
        }
        else
        {
            return $this->error('Unknown error',520);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
