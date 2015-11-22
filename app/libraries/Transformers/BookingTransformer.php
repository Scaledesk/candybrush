<?php
/**
 * Created by PhpStorm.
 * User: tushar
 * Date: 20/10/15
 * Time: 12:42 PM
 */

namespace app\libraries\Transformers;


use App\Booking;
use App\Booking_Packages_Addons;
use App\Booking_Packages_Installments;
use App\Bookings_Package_Tags;
use App\Bookings_Packages_Bonus;
use Illuminate\Support\Facades\Input;
use League\Fractal\TransformerAbstract;
use App\libraries\Transformers\UserTransformer;
use App\libraries\Transformers\PackagesTransformer;
use App\libraries\Transformers\Booking_Packages_Installments_Transformer;
use App\libraries\Transformers\Bookings_Package_Tags_TransFormer;
use App\libraries\Transformers\Bookings_Packages_Bonus_Transformer;
use App\libraries\Transformers\Booking_Packages_Addons_Transformer;


class BookingTransformer extends TransformerAbstract{
    protected $defaultIncludes=['Buyer','Package','bookingPackagesInstallments','bookingsPackagesBonus','bookingPackagesAddons','bookingPackagesTags'];
    public function transform(Booking $booking){

        return [
            'id'                           =>$booking[Booking::ID],
            'package_id'                   =>$booking[Booking::PACKAGE_ID],
            'seller_id'                    =>$booking[Booking::SELLER_ID],
            'buyer_id'                     =>$booking[Booking::BUYER_ID],
            'price'                        =>$booking[Booking::PRICE],
            'deal_price'                   =>$booking[Booking::DEAL_PRICE],
            'package_duration'             =>$booking[Booking::PACKAGE_DURATION],
            'package_description'          =>$booking[Booking::PACKAGE_DESCRIPTION],
            'package_title'                =>$booking[Booking::PACKAGE_TITLE],
            'package_category_id'          =>$booking[Booking::PACKAGE_CATEGORY_ID],
            'package_available_dates'      =>$booking[Booking::PACKAGE_AVILABLE_DATES],
            'package_term'                 =>$booking[Booking::PACKAGE_TERM_CONDITION],
            'package_average_rating'       =>$booking[Booking::PACKAGE_AVERAGE_RATING],
            'package_location'             =>$booking[Booking::PACKAGE_LOCATION],
            'package_instruction'          =>$booking[Booking::PACKAGE_INSTRUCTIONS],
            'package_delivery_time'        =>$booking[Booking::PACKAGE_DELIVERY_TIME],
            'package_delivery_time_type'   =>$booking[Booking::PACKAGE_DELIVERY_TIME_TYPE],
            'package_meeting_availability' =>$booking[Booking::PACKAGE_MEETING_AVAILABILITY],
            'package_meeting_address'      =>$booking[Booking::PACKAGE_MEETING_ADDRESS],
            'package_delivery_type_id'     =>$booking[Booking::PACKAGE_DELIVERY_TYPE_ID],
            'package_delivery_type_name'   =>$booking[Booking::PACKAGE_DELIVERY_TYPE_NAME],
            'package_type_id'              =>$booking[Booking::PACKAGE_TYPE_ID],
            'package_type_name'            =>$booking[Booking::PACKAGE_TYPE_NAME],
            'booking_status'               =>$booking[Booking::BOOKING_STATUS],
            'package_timestamp'            =>$booking[Booking::PACKAGE_TIMESTAMP],
            'payment_type'                 =>$booking[Booking::PAYMENT_TYPE],
            'payment_status'               =>$booking[Booking::PAYMENT_STATUS]
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
       // print_r($booking->buyer()->first());
        //return $this->item($booking->buyer()->first(),new UserTransformer());
    }

    /**
     * include Package i.e. sold item
     * @param Booking $booking
     * @return \League\Fractal\Resource\Item
     */
    public function includePackage(Booking $booking){
       // return $this->item($booking->package()->first(),new PackagesTransformer());
    }
    public function includeBookingPackagesInstallments(Booking $booking){
        return $this->collection($booking->bookingPackagesInstallments()->get(),new Booking_Packages_Installments_Transformer());
    }
    public function includeBookingPackagesTags(Booking $booking){
        return $this->collection($booking->bookingPackagesTags()->get(),new Bookings_Package_Tags_TransFormer());
    }
    public function includeBookingPackagesAddons(Booking $booking){
        return $this->collection($booking->bookingPackagesAddons()->get(),new Booking_Packages_Addons_Transformer());
    }
    public function includeBookingsPackagesBonus(Booking $booking){
        return $this->collection($booking->bookingsPackagesBonus()->get(),new Bookings_Packages_Bonus_Transformer());
    }
}