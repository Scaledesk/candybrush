<?php
/**
 * Created by PhpStorm.
 * User: tushar
 * Date: 22/11/15
 * Time: 2:00 PM
 */

namespace app\libraries\Transformers;


use App\Booking_Packages_Installments;
use League\Fractal\TransformerAbstract;

class Booking_Packages_Installments_Transformer extends TransformerAbstract
{
    public function transform(Booking_Packages_Installments $booking_Packages_Installments){
        return [
            "id"=>$booking_Packages_Installments->candybrush_bookings_packages_installments_id,
            "bookings_id"=>$booking_Packages_Installments->candybrush_bookings_packages_installments_bookings_id,
            "installment_id"=>$booking_Packages_Installments->candybrush_bookings_packages_installments_installment_id,
            "installment_number"=>$booking_Packages_Installments->candybrush_bookings_packages_installments_installment_number,
            "installment_amount"=>$booking_Packages_Installments->candybrush_bookings_packages_installments_installment_amount,
        ];
    }

}