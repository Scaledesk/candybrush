<?php

namespace App\Http\Controllers;

use app\libraries\Transformers\ReferralTransformer;
use App\Referral;
use App\User;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ReferralController extends BaseController
{
    protected $referral_transformer;

    /**
     * ReferralController constructor.
     */
    public function __construct()
    {
        $this->referral_transformer=new ReferralTransformer();
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
        $data=$this->referral_transformer->requestAdaptorForReferring();
        $validation_result=$this->my_validate([
            'data'=>$data,
            'rules'=>[
                Referral::USER_ID=>'required|numeric|exists:users,id',
                Referral::REFFERED_USER_EMAIL=>'required|email',
                Referral::REFERRAL_CODE=>'required'
            ],
            'messages'=>[
                'user_id.required'=>'user id is required, give id in url user\<user_id>',
                'user_id.numeric'=>'only numbers are allowed in user_id',
                'user_id.exists'=>'user id do not match any records',
                Referral::REFFERED_USER_EMAIL.'.required'=>'email of whow you want to reffer is required try referred_user_email=<valid email id>',
                Referral::REFFERED_USER_EMAIL.'.email'=>'email must be a valid email id',
                Referral::REFERRAL_CODE.'.required'=>' some error occurred we are unable to generate that one referral code is required, try again'
            ]
        ]);
        if($validation_result['result']){
            $referral = new Referral($data);
            return DB::transaction(function()use($referral){
                $referral->save();
                return $this->success();
            });

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
        //
    }
}
