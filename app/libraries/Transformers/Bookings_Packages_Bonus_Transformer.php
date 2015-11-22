<?php
/**
 * Created by PhpStorm.
 * User: tushar
 * Date: 22/11/15
 * Time: 2:17 PM
 */

namespace app\libraries\Transformers;


use App\Bookings_Packages_Bonus;
use League\Fractal\TransformerAbstract;

class Bookings_Packages_Bonus_Transformer extends TransformerAbstract
{
    public function transform(Bookings_Packages_Bonus $bookings_Packages_Bonus){
     return [
         "id"=>$bookings_Packages_Bonus->candybrush_bookings_bonus_id,
         "name"=>$bookings_Packages_Bonus->candybrush_bookings_bonus_name,
         "description"=>$bookings_Packages_Bonus->candybrush_bookings_bonus_description,
         "package_id"=>$bookings_Packages_Bonus->candybrush_bookings_bonus_package_id,
         "bonus_id"=>$bookings_Packages_Bonus->candybrush_bookings_bonus_bonus_id,
         "bookings_id"=>$bookings_Packages_Bonus->candybrush_bookings_bonus_bookings_id
     ];
    }
}