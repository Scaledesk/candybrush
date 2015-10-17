<?php

namespace App\Http\Controllers;

use App\Bonus;
use App\libraries\Constants;
use App\libraries\Transformers\BonusTransformer;
use App\PackagesModel;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class BonusController extends BaseController
{
    protected $bonus_transformer;

    function __construct()
    {
        $this->bonus_transformer = new BonusTransformer();
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
        $data=$this->bonus_transformer->requestAdaptor();
        $validation_result=$this->my_validate([
            'data'=>$data,
            'rules'=>[
                Bonus::NAME=>'required',
                Bonus::DESCRIPTION=>'required|min:10',
                Bonus::PACKAGE_ID=>'required|exists:candybrush_packages,id'
            ],
            'messages'=>[
                Bonus::NAME.'.required'=>'Bonus Name must be required try name=<name>',
                Bonus::DESCRIPTION.'.required'=>'Bonus Description must be required try description=<description>',
                Bonus::PACKAGE_ID.'.required'=>'Package Id is required try package_id=<package_id>',
                Bonus::NAME.'.unique'=>'Bonus Name already exist in related package try with different name',
                Bonus::PACKAGE_ID.'.exists'=>'Package id do not match any records, try with different package id',
            ]
        ]);
        $checkUniqueValidationForNameOfBonus =function($data){
            if(DB::table('candybrush_bonus')->where('candybrush_bonus_name','=',$data[Bonus::NAME])->where('candybrush_bonus_package_id','=',$data[Bonus::PACKAGE_ID])->count()>0){
                return false;
            }else{
                return true;
            }
        };
        if($validation_result['result']){
            if($checkUniqueValidationForNameOfBonus($data)){
                $package=PackagesModel::find($data[Bonus::PACKAGE_ID]);
                $bonus=new Bonus($data);
                $package->bonus()->save($bonus);
                return $this->success();
            }
            else{
                return $this->error('Bonus with same name in the package already exists try with different name',422);
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
                'id'=>'required|exists:'.Constants::PREFIX.'bonus,'.Constants::PREFIX.'bonus_id'
            ],
            'messages'=>[
                'id.required'=>'bonus id is required to delete bonus try in url bonus/<id>',
                'id.exists'=>'bonus id do not match any records, it not exists or already deleted'
            ]
        ]);
        if($validation_result['result']){
            if(is_null(Bonus::find($id)->delete())){
                return $this->error('unknown error occurred',520);
            }else{return $this->success();}
        }else{
            return $validation_result['error'];
        }
    }
}
