<?php
/**
 * Created by PhpStorm.
 * User: tushar
 * Date: 9/10/15
 * Time: 7:57 PM
 */

namespace app\libraries\Transformers;


use App\UserWallet;
use Illuminate\Support\Facades\Input;
use League\Fractal\TransformerAbstract;

class UserwalletTransformer extends TransformerAbstract{
    //for object transformation
    public function transform(UserWallet $userWallet){
        return [
            'amount'=>$userWallet->candybrush_users_wallet_amount
        ];
    }
    public function requestAdaptor(){
        /*
         * callable to set transaction type if no description given
         */
        $setDescription=function(){
            switch(strtolower(Input::get('transaction_type'))){
                case 'credit':{
                    return 'Credit '.Input::get('amount').' to user wallet';
                }
                case 'debit':{
                    return 'Debit '.Input::get('amount').' from user wallet';
                }
                default:{
                    return 'unresolved transaction_type';
                }
            }
        };
        return[
            UserWallet::ID => Input::get('user_id'),
            UserWallet::AMOUNT=>Input::get('amount'),
            UserWallet::TRANSTYPE=>Input::get('transaction_type'),//transaction_type
            UserWallet::DESCRIPTON=>Input::get('description',$setDescription())];
    }
}