<?php
/**
 * Created by PhpStorm.
 * User: Javed
 * Date: 7/10/15
 * Time: 7:23 PM
 */
namespace App\libraries\Transformers;
use App\Booking;
use App\UserProfile;
use App\UserWallet;
use Dingo\Api\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use League\Fractal\TransformerAbstract;
use App\libraries\Transformers\UserwalletTransformer;

class UserProfileTransformer extends TransformerAbstract{
    public function transform(UserProfile $profile){
        return [
            'name'=>$profile->candybrush_users_profiles_name,
            'user_id'=>$profile->candybrush_users_profiles_users_id,
            'mobile'=>$profile->candybrush_users_profiles_mobile,
            'address'=>$profile->candybrush_users_profiles_address,
            'state'=>$profile->candybrush_users_profiles_state,
            'city'=>$profile->candybrush_users_profiles_city,
            'pin'=>$profile->candybrush_users_profiles_pin,
            'language_known'=>$profile->candybrush_users_profiles_language_known,
            'description'=>$profile->candybrush_users_profiles_description,
            'custom_message'=>$profile->candybrush_users_profiles_custom_message,
            'birth_date'=>$profile->candybrush_users_profiles_birth_date,
            'sex'=>$profile->candybrush_users_profiles_sex,
            'image'=>$profile->candybrush_users_profiles_image,
            'id_proof'=>$profile->candybrush_users_profiles_id_proof,
            'sales'=>self::getTotalSales($profile),
            'purchases'=>self::getTotalPurchases($profile),
            'wallet'=>self::getWallet($profile)
        ];
    }
    public function requestAdapter()
    {
        return [
            UserProfile::NAME => Input::get('name'),
            UserProfile::MOBILE => Input::get('mobile'),
            UserProfile::ADDRESS => Input::get('address'),
            UserProfile::STATE => Input::get('state'),
            UserProfile::CITY => Input::get('city'),
            UserProfile::PIN => Input::get('pin'),
            UserProfile::LANGUAGE_KNOWN =>Input::get('language_known'),
            UserProfile::DESCRIPTION => Input::get('description'),
            UserProfile::SOCIAL_ACCOUNT_INTEGRATION => Input::get('social_account_integration'),
            UserProfile::CUSTOM_MESSAGE => Input::get('custom_message'),
            UserProfile::BIRTH_DATE => Input::get('birth_date'),
            UserProfile::SEX => Input::get('sex'),
            UserProfile::IMAGE => Input::get('image'),
            UserProfile::ID_PROOF => Input::get('id_proof')
        ];
    }
    public function getTotalSales(UserProfile $userProfile){
        $data=Booking::where('candybrush_bookings_seller_id',$userProfile->candybrush_users_profiles_users_id)->select(Booking::PRICE)->get();
        $sum=0;
        $count=0;
        foreach($data as $price){
            $sum+=$price->candybrush_bookings_price;
            $count++;
            }
        unset($data,$price);
        return ["amount"=>$sum,
            "count"=>$count];
        }
    public function getTotalPurchases(UserProfile $userProfile){
        $data=Booking::where('candybrush_bookings_buyer_id',$userProfile->candybrush_users_profiles_users_id)->select(Booking::PRICE)->get();
        $sum=0;
        $count=0;
        foreach($data as $price){
            $sum+=$price->candybrush_bookings_price;
            $count++;
        }
        unset($data,$price);
        return ["amount"=>$sum,
                "count"=>$count];
        }
        public function getWallet(UserProfile $userProfile){
            $transformer=new UserwalletTransformer();
            $userWallet=UserWallet::where('candybrush_users_wallet_user_id',$userProfile->candybrush_users_profiles_users_id)->first();
            if(is_null($userWallet)){
                return null;
            }
            return $transformer->transform($userWallet);
        }
}