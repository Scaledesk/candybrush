<?php
/**
 * Created by PhpStorm.
 * User: tushar
 * Date: 22/11/15
 * Time: 2:07 PM
 */

namespace app\libraries\Transformers;


use App\Bookings_Package_Tags;
use App\Tag;
use League\Fractal\TransformerAbstract;

class Bookings_Package_Tags_TransFormer extends TransformerAbstract
{
    public function transform(Bookings_Package_Tags $bookings_Package_Tags){
        return [
            'id'=>$bookings_Package_Tags->candybrush_bookings_packages_tags_id,
            'bookings_id'=>$bookings_Package_Tags->candybrush_bookings_packages_tags_bookings_id,
            'tag_id'=>$bookings_Package_Tags->candybrush_bookings_packages_tags_tag_id,
            'tag_name'=>self::getTagName($bookings_Package_Tags)
        ];
    }
    public function getTagName(Bookings_Package_Tags $bookings_Package_Tags){
        return Tag::where(Tag::ID,$bookings_Package_Tags->candybrush_bookings_packages_tags_tag_id)->select(Tag::NAME)->first()->candybrush_tags_name;
    }
}