<?php
/**
 * Created by PhpStorm.
 * User: tushar
 * Date: 22/11/15
 * Time: 2:14 PM
 */

namespace app\libraries\Transformers;


use App\Booking_Packages_Addons;
use App\Booking_Packages_Installments;
use League\Fractal\TransformerAbstract;

class Booking_Packages_Addons_Transformer extends TransformerAbstract
{
    public function transform(Booking_Packages_Addons $booking_Packages_Addons){
        return [
            "id"=>$booking_Packages_Addons->candybrush_bookings_addons_id,
            "name"=> $booking_Packages_Addons->candybrush_bookings_addons_name,
            "description"=>$booking_Packages_Addons->candybrush_bookings_addons_description,
            "terms"=>$booking_Packages_Addons->candybrush_bookings_addons_terms,
            "package_id"=>$booking_Packages_Addons->candybrush_bookings_addons_package_id,
            "addon_id"=>$booking_Packages_Addons->candybrush_bookings_addons_addon_id,
            "bookings_id"=>$booking_Packages_Addons->candybrush_bookings_addons_bookings_id,
            "amount"=>$booking_Packages_Addons->candybrush_bookings_addons_amount,
            "days"=>$booking_Packages_Addons->candybrush_bookings_addons_days
        ];
    }
}