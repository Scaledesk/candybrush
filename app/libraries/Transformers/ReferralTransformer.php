<?php
/**
 * Created by PhpStorm.
 * User: Coder
 * Date: 10/30/2015
 * Time: 6:17 PM
 */

namespace app\libraries\Transformers;


use App\Referral;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use League\Fractal\TransformerAbstract;

class ReferralTransformer extends TransformerAbstract
{
    public function transform(Referral $referral){
        return[
            'id'=>$referral[Referral::ID],
            'user_id'=>$referral[Referral::USER_ID],
            'referred_user_id'=>$referral[Referral::REFFERED_USER_ID],
            'referred_user_email'=>$referral[Referral::REFFERED_USER_EMAIL],
            'referral_code'=>$referral[Referral::REFERRAL_CODE],
            'success'=>is_null($referral[Referral::REFFERED_USER_ID])?false:true
        ];
    }
    public function requestAdaptorForReferring(){
        return [
            Referral::USER_ID=>Input::get('user_id',''),
            Referral::REFFERED_USER_EMAIL=>Input::get('referred_user_email',''),
            Referral::REFERRAL_CODE=>str_random(30),
        ];
    }
}