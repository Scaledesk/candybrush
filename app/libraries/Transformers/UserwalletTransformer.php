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
    public function transform(){
        //return [];
    }
    public function requestAdaptor(){
        return[
            UserWallet::ID => Input::get('user_id'),
            UserWallet::AMOUNT=>Input::get('amount'),
            UserWallet::TRANSTYPE=>Input::get('transaction_type'),//transaction_type
            UserWallet::DESCRIPTON=>Input::get('description')];
    }
}