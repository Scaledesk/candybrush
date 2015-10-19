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
    function __construct()
    {
        $this->packageTransformer = new PackagesTransformer();
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
                PackagesModel::CATEGORY_ID.'.required'=>'Category_id is required try category_id=<category_id>',
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

    public function update($id)
    {
        $package=PackagesModel::where('id',$id)->first();
        if(is_null($package)){
            return $this->error('PackageId do nat match any records, please try again',404);
        }
        $data = $this->packageTransformer->requestAdapter();
        $data=array_filter($data,'strlen'); // filter blank or null array
        if(sizeof($data)) {
           $result= DB::transaction(function()use($package,$data){
            try {
                $result =/*PackagesModel::where('id', $id)->*/
                    $package->update($data);
                if($result)
                {
                    return $this->success();
                }
                else
                {
                    return $this->error('Unknown error',520);
                }
            } catch (\Exception $e) {
                return $this->error($e->getMessage(), $e->getCode());
            }
        });
            return $result;
        }else{
            return $this->error('no adequate field passed',422);
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

    /**
     * function to set package ready to publish
     * this function may also send review request to admin then it will be published on passed
     */
    public function setCompleted($id){
        $package=PackagesModel::where('id',$id)->first();
        if(is_null($package)){
            return $this->error('PackageId do nat match any records, please try again',404);
        }
        try{
            $result=DB::transaction(function()use($package){
                $package->candybrush_packages_completed="yes";
                $package->update();
                /**
                 * code here to send review request to admin
                 */
                return $this->success('Your package details submitted successfully and sent to admin for review, later published');
            });
            return $result;
        }catch(Exception $e){
            return $this->error('some unknown error occurred',520);
        }
    }
}
