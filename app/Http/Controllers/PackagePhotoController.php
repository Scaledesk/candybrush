<?php

namespace App\Http\Controllers;

use App\libraries\Transformers\PackagePhotoTransformer;
use App\PackagePhoto;
use App\PackagesModel;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class PackagePhotoController extends BaseController
{
    protected $package_photo_transformer;

    function __construct()
    {
        $this->package_photo_transformer=new PackagePhotoTransformer();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        if(!is_numeric($id)){
        return $this->error('Only numbers are allowed as package Id');
    }
    $package=PackagesModel::find($id);
        if(is_null($package)){
            return $this->error('package id do not match any records');
        }
        return $this->response()->collection($package->photos()->get(),$this->package_photo_transformer);
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

    /*public function store(Request $request)
    {
        $data=$this->package_photo_transformer->requestAdaptor();
        $validation_result=$this->my_validate([
            'data'=>$data,
            'rules'=>[
            PackagePhoto::URL=>'required',
                PackagePhoto::PACKAGE_ID=>'required|numeric|exists:candybrush_packages,id',
            ],
            'messages'=>[
                PackagePhoto::URL.'.required'=>'photo url is required try url=<url of the photo>',
                PackagePhoto::PACKAGE_ID.'.required'=>'package id is required try package_id=<package_id of the photo>',
                PackagePhoto::PACKAGE_ID.'.numeric'=>'only numbers are allowed as package id',
                PackagePhoto::PACKAGE_ID.'.exists'=>'package id do not match any records',
            ]
        ]);
        if($validation_result['result']){
            PackagePhoto::create([$data]);
            return $this->success();
        }else{
            return
            $validation_result['error'];
        }
    }*/
    /**
     * function to store photos in bulk for a package
     * @return mixed
     */
    public function store(){
        $data=$this->package_photo_transformer->requestAdaptor();
        if(is_null($data)){
            return $this->error('please give data in array like "package_id":"package_id",
            data:[
            ["url":"url of package_photo1",
            ], [package_photo2], [package_photo3] etc]',422);
        }
        $package_id=$data['package_id'];
        unset($data['package_id']);
        $package=PackagesModel::where('id',$package_id)->first();
        if(is_null($package)){
            unset($package);
            return $this->error('Package_id do not match any records!',404);
        }
        unset($package);
       /* print_r($data);
        die;*/
        foreach($data as $package_photo){
            $validation_result=$this->my_validate([
                'data'=>$package_photo,
                'rules'=>[
                    PackagePhoto::URL=>'required',
                ],
                'messages'=>[
                    PackagePhoto::URL.'.required'=>'photo url is required try url=<url of the photo>',
                ]
            ]);
            if(!$validation_result['result']){
                return $validation_result['error'];
            }
        }
        $package_photo_bulk=array();
        foreach($data as $package_photo){
                $package_photo[PackagePhoto::PACKAGE_ID]=$package_id;
                array_push($package_photo_bulk,$package_photo);

        }
        $result=DB::transaction(function()use($package_photo_bulk){
            try{
                DB::table('candybrush_packages_photos')->insert($package_photo_bulk);
                return $this->success();
            }catch(\Exception $e){
                echo $e->getMessage();
//                return $this->error('unknown error occurred',520);
            }
        });
        unset($package_id);
        unset($package_photo_bulk);
        unset($package_photo);
        return $result;

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(!is_numeric($id)){
            return $this->error('Only numbers are allowed as package photo Id');
        }
        $package_photo=PackagePhoto::find($id);
        if(is_null($package_photo)){
            return $this->error('package photo id do not match any records');
        }
        return $this->response()->item($package_photo,$this->package_photo_transformer);
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
        //
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
