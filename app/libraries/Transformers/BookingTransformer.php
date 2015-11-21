<?php
/**
 * Created by PhpStorm.
 * User: tushar
 * Date: 20/10/15
 * Time: 12:42 PM
 */

namespace app\libraries\Transformers;


use App\Booking;
use Illuminate\Support\Facades\Input;
use League\Fractal\TransformerAbstract;
use App\libraries\Transformers\UserTransformer;
use App\libraries\Transformers\PackagesTransformer;

class BookingTransformer extends TransformerAbstract{
    protected $defaultIncludes=['Buyer','Package'];
    public function transform(Booking $booking){
        return [
            'id'=>$booking[Booking::ID],
            'payment_type'=>$booking[Booking::PAYMENT_TYPE],
            'payment_status'=>$booking[Booking::PAYMENT_STATUS]
        ];
    }
    public function requestAdaptor(){

        return [
            //necessary feilds
            Booking::BUYER_ID=>Input::get('user_id',''),
            Booking::PACKAGE_ID=>Input::get('package_id',''),
            Booking::PAYMENT_TYPE=>Input::get('payment_type'),
            Booking::PACKAGE_DURATION=>NULL,
            Booking::BOOKING_STATUS=>"pending",
            Booking::PAYMENT_STATUS=>"not_completed",
            Booking::PACKAGE_TIMESTAMP=>Input::get("package_timestamp"),
            "addons_id"=>Input::get("addons",'none'),
            "bonus_id"=>Input::get("bonus_id",'none'),
            "installments_id"=>Input::get("installments_id",'none'),
        ];
    }
    /**
     * include Buyer i.e. User
     * @param Booking $booking
     * @return \League\Fractal\Resource\Item
     */
    public function includeBuyer(Booking $booking){
        return $this->item($booking->buyer()->first(),new UserTransformer());
    }

    /**
     * include Package i.e. sold item
     * @param Booking $booking
     * @return \League\Fractal\Resource\Item
     */
    public function includePackage(Booking $booking){
        return $this->item($booking->package()->first(),new PackagesTransformer());
    }
}