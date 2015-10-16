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
        $validation_result=$this->my_validate([
            'data'=>$data,
            'rules'=>[
                Addon::NAME=>'required',
                Addon::DESCRIPTION=>'required|min:10',
                Addon::PACKAGE_ID=>'required|exists:candybrush_packages,id',
                Addon::DAYS=>'required|numeric',
                Addon::PRICE=>'required|numeric'
            ],
            'messages'=>[
                Addon::NAME.'.required'=>'Addon Name must be required try name=<name>',
                Addon::DESCRIPTION.'.required'=>'Addon Description must be required try description=<description>',
                Addon::PACKAGE_ID.'.required'=>'Package Id is required try package_id=<package_id>',
                Addon::NAME.'.unique'=>'Addon Name already exist in related package try with different name',
                Addon::PACKAGE_ID.'.exists'=>'Package id do not match any records, try with different package id',
                Addon::NAME.'.required'=>'Number of days required for Addon try days=<no of days>',
                Addon::PRICE.'.required'=>'Price of Addon required, try price=<price of addon>',
                Addon::DAYS.'.numeric'=>'Only numbers are required as number of days',
                Addon::PRICE.'.numeric'=>'Only numbers are required as price of Addon',

            ]
        ]);
        $checkUniqueValidationForNameOfAddon =function($data){
            if(DB::table('candybrush_addons')->where('candybrush_addons_name','=',$data[Addon::NAME])->where('candybrush_addons_package_id','=',$data[Addon::PACKAGE_ID])->count()>0){
                return false;
            }else{
                return true;
            }
        };
        if($validation_result['result']){
            if($checkUniqueValidationForNameOfAddon($data)){
                /*$package=PackagesModel::find($data[Addon::PACKAGE_ID]);
                $addon=new Addon($data);
                $package->addons()->save($addon);
                $package->candybrush_packages_price=$package->candybrush_packages_price+$addon->candybrush_addons_price;
                $package->candybrush_packages_deal_price=$package->candybrush_packages_deal_price+  $addon->candybrush_addons_price;
                $package->update();
                return $this->success();*/
                $package=PackagesModel::find($data[Addon::PACKAGE_ID]);
                $addon=new Addon($data);
                $package->addons()->save($addon);
                return $this->success();
            }
            else{
                return $this->error('Addon with same name in the package already exists try with different name',422);
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
