<?php

namespace App\Http\Controllers;

use App\Addon;
use App\libraries\Constants;
use App\libraries\Transformers\AddonTransformer;
use App\PackagesModel;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Mockery\CountValidator\Exception;

class AddonController extends BaseController
{
    protected $addon_transformer;

    function __construct()
    {
        $this->addon_transformer = new AddonTransformer();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    $data=$this->addon_transformer->requestAdaptor();
        if(is_null($data)){
            return $this->error('please give data in array like "package_id":"package_id",
            data:[
            ["name":"name of addon1",
            "description":"description of addon 1",
            "days":"days increased",
            "price":"price of addon"
            ], [addon2], [addon3] etc]',422);
        }
        $package_id=$data['package_id'];
        unset($data['package_id']);
        $package=PackagesModel::where('id',$package_id)->first();
        if(is_null($package)){
            unset($package);
            return $this->error('Package_id do not match any records!',404);
        }
        unset($package);
        foreach($data as $addon){
            $validation_result=$this->my_validate([
                'data'=>$addon,
                'rules'=>[
                    Addon::NAME=>'required',
                    Addon::DESCRIPTION=>'required|min:10',
                    Addon::DAYS=>'required|numeric',
                    Addon::PRICE=>'required|numeric'
                ],
                'messages'=>[
                    Addon::NAME.'.required'=>'Addon Name must be required try name=<name>',
                    Addon::DESCRIPTION.'.required'=>'Addon Description must be required try description=<description>',
                    Addon::NAME.'.unique'=>'Addon Name already exist in related package try with different name',
                    Addon::DAYS.'.required'=>'Number of days required for Addon try days=<no of days>',
                    Addon::PRICE.'.required'=>'Price of Addon required, try price=<price of addon>',
                    Addon::DAYS.'.numeric'=>'Only numbers are required as number of days',
                    Addon::PRICE.'.numeric'=>'Only numbers are required as price of Addon',

                ]
            ]);
            if(!$validation_result['result']){
                return $validation_result['error'];
            }
        }
        $addon_bulk=array();
        foreach($data as $addon){
            $checkUniqueValidationForNameOfAddon =function($addon)use($package_id){
                if(DB::table('candybrush_addons')->where('candybrush_addons_name','=',$addon[Addon::NAME])->where('candybrush_addons_package_id','=',$package_id)->count()>0){
                    return false;
                }else{
                    return true;
                }
            };
            if($checkUniqueValidationForNameOfAddon($addon)){
               /* $package=PackagesModel::find($addon[Addon::PACKAGE_ID]);
                $addon=new Addon($addon);
                $package->addons()->save($addon);*/
                $addon[Addon::PACKAGE_ID]=$package_id;
                array_push($addon_bulk,$addon);
            }
            else{
                return $this->error('Addon with same name in the package already exists try with different name',422);
            }
        }
        $hasDuplicates=function($addon_bulk){
            $addon_names=array();
            foreach($addon_bulk as $addon){
                array_push($addon_names,$addon['candybrush_addons_name']);
            }
            if(count(array_unique($addon_names))<count($addon_bulk))
            {
                // Array has duplicates
                return true;
            }
            else
            {
                // Array does not have duplicates
                return false;
            }
        };
        if($hasDuplicates($addon_bulk)){
            return $this->error('your given array of addons has duplicate names! please check',422);
        }
        $result=DB::transaction(function()use($addon_bulk){
            try{
                DB::table('candybrush_addons')->insert($addon_bulk);
                return $this->success();
            }catch(Exception $e){
                return $this->error('unknown error occurred',520);
            }
        });
        unset($package_id);
        unset($addon_bulk);
        unset($addon);
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
        //
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
        $data=['id'=>$id];
        $validation_result=$this->my_validate(['data'=>$data,
    'rules'=>[
        'id'=>'required|exists:'.Constants::PREFIX.'addons,'.Constants::PREFIX.'addons_id'
    ],
        'messages'=>[
        'id.required'=>'addon id is required to delete addon try in url addon/<id>',
            'id.exists'=>'addon id do not match any records, it not exists or already deleted'
        ]
    ]);
     if($validation_result['result']){
         if(is_null(Addon::find($id)->delete())){
             return $this->error('unknown error occurred',520);
         }else{return $this->success();}
         /*$addon=Addon::find($id);
         $price=$addon->package()->first();
         $price->candybrush_packages_price=$price->candybrush_packages_price-$addon->candybrush_addons_price;
         $price->candybrush_packages_deal_price=$price->candybrush_packages_deal_price-$addon->candybrush_addons_price;
         $price->update();
         $addon->delete();
         return $this->success();*/
     }else{
         return $validation_result['error'];
     }
    }
}
