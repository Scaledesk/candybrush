<?php

namespace App\Http\Controllers;

use App\libraries\Transformers\UserwalletTransformer;
use App\User;
use App\UserWallet;
use App\UserWalletTransactions;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class UserwalletController extends BaseController
{
    protected $transformer;

    function __construct()
    {
        $this->transformer = new UserwalletTransformer();
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
        //
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
     * @return \Illuminate\Http\Response
     * @internal param Request $request
     * @internal param int $id
     */
    public function update()
    {
        $data=$this->transformer->requestAdaptor();
        /*
         * lazy loading i.e. lazy mans method.....callable methods
         */
        /*
         * Credit money to wallet
         */
        $credit=function()use($data){
            if($user=User::find($data[UserWallet::ID])){
                DB::transaction(function()use($data,$user){
                    User::find($data[UserWallet::ID])->UserWallet->update([
                        UserWallet::AMOUNT
                        =>
                            User::find($data[UserWallet::ID])->UserWallet->first([UserWallet::AMOUNT])->candybrush_users_wallet_amount+$data[UserWallet::AMOUNT]]);
                    $user->UserWallet->transactions()->save(new UserWalletTransactions([
                        'candybrush_users_wallet_transactions_description'=>$data[UserWallet::DESCRIPTON],
                        'candybrush_users_wallet_transactions_type'=>$data[UserWallet::TRANSTYPE],
                        'candybrush_users_wallet_transactions_amount'=>$data[UserWallet::AMOUNT]
                    ]));
                });
                return $this->success();
            }else{
                return $this->error("user_id do not match any records");
            }
        };
        /*
         * Debit money from wallet
         */
        $debit=function()use($data){
            if($user=User::find($data[UserWallet::ID])){
                DB::transaction(function()use($data,$user){
                    User::find($data[UserWallet::ID])->UserWallet->update([
                        UserWallet::AMOUNT
                        =>
                            User::find($data[UserWallet::ID])->UserWallet->first([UserWallet::AMOUNT])->candybrush_users_wallet_amount-$data[UserWallet::AMOUNT]]);
                    $user->UserWallet->transactions()->save(new UserWalletTransactions([
                        'candybrush_users_wallet_transactions_description'=>$data[UserWallet::DESCRIPTON],
                        'candybrush_users_wallet_transactions_type'=>$data[UserWallet::TRANSTYPE],
                        'candybrush_users_wallet_transactions_amount'=>$data[UserWallet::AMOUNT]
                    ]));
                });
                return $this->success();
            }else{
                return $this->error("user_id do not match any records");
            }
        };
        switch(strtolower($data[UserWallet::TRANSTYPE]))
        {
            case 'credit':{
                return $credit();
                break;
            }
            case 'debit':{
                return $debit();
                break;
            }
            default :{
                return $this->error('only credit or debit transaction_type allowed',422);
            }
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
