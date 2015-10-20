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
        if(is_null($data)){
            return $this->error('please give data in array like "package_id":"package_id",
            data:[
            ["name":"name of bonus1",
            "description":"description of bonus 1",
            "days":"days increased",
            "price":"price of bonus"
            ], [bonus2], [bonus3] etc]',422);
        }
        $package_id=$data['package_id'];
        unset($data['package_id']);
        $package=PackagesModel::where('id',$package_id)->first();
        if(is_null($package)){
            unset($package);
            return $this->error('Package_id do not match any records!',404);
        }
        unset($package);
        foreach($data as $bonus){
            $validation_result=$this->my_validate([
                'data'=>$bonus,
                'rules'=>[
                    Bonus::NAME=>'required',
                    Bonus::DESCRIPTION=>'required|min:10',
                ],
                'messages'=>[
                    Bonus::NAME.'.required'=>'Bonus Name must be required try name=<name>',
                    Bonus::DESCRIPTION.'.required'=>'Bonus Description must be required try description=<description>',
                    Bonus::NAME.'.unique'=>'Bonus Name already exist in related package try with different name'
                ]
            ]);
            if(!$validation_result['result']){
                return $validation_result['error'];
            }
        }
        $bonus_bulk=array();
        foreach($data as $bonus){
            $checkUniqueValidationForNameOfBonus =function($bonus)use($package_id){
                if(DB::table('candybrush_bonus')->where('candybrush_bonus_name','=',$bonus[Bonus::NAME])->where('candybrush_bonus_package_id','=',$package_id)->count()>0){
                    return false;
                }else{
                    return true;
                }
            };
            if($checkUniqueValidationForNameOfBonus($bonus)){
                /* $package=PackagesModel::find($bonus[Bonus::PACKAGE_ID]);
                 $bonus=new Bonus($bonus);
                 $package->bonus()->save($bonus);*/
                $bonus[Bonus::PACKAGE_ID]=$package_id;
                array_push($bonus_bulk,$bonus);
            }
            else{
                return $this->error('Bonus with same name in the package already exists try with different name',422);
            }
        }
        $hasDuplicates=function($bonus_bulk){
            $bonus_names=array();
            foreach($bonus_bulk as $bonus){
                array_push($bonus_names,$bonus['candybrush_bonus_name']);
            }
            if(count(array_unique($bonus_names))<count($bonus_names))
            {
                // Array has duplicates
                unset($bonus_names);
                return true;
            }
            else
            {
                // Array does not have duplicates
                return false;
            }
        };
        if($hasDuplicates($bonus_bulk)){
            return $this->error('your given array of bonus has duplicate names! please check',422);
        }
        $result=DB::transaction(function()use($bonus_bulk){
            try{
                DB::table('candybrush_bonus')->insert($bonus_bulk);
                return $this->success();
            }catch(Exception $e){
                return $this->error('unknown error occurred',520);
            }
        });
        unset($package_id);
        unset($bonus_bulk);
        unset($bonus);
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
