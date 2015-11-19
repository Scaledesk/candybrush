<?php
/**
 * Created by PhpStorm.
 * User: tushar
 * Date: 8/11/15
 * Time: 6:51 PM
 */

namespace app\libraries\Transformers;


use App\PackagePhoto;
use App\PackagesModel;
use Illuminate\Support\Facades\DB;
use League\Fractal\TransformerAbstract;
use App\libraries\Transformers\PackagePhotoTransformer;

class FirstTimePackageTransformer extends TransformerAbstract{
    public function transform(PackagesModel $package){
        return [
            'id'=>$package->id,
            'name'=>$package->candybrush_packages_name,
            'seller_name'=>self::getsellerName($package),
            'price'=>(integer)$package->candybrush_packages_price,
            'deal_price'=>(integer)$package->candybrush_packages_deal_price,
            'addon_available'=>self::isAddonAvailable($package),
            'bonus_available'=>self::isBonusAvailable($package),
            'first_photo'=>self::getFirstPhoto($package),
            'maximum_delivery_days'=>$package->candybrush_packages_maximum_delivery_days,
            'average_rating'=>self::getAverageRating($package)
        ];
    }
    /**
     * for including Addons
     * @param PackagesModel $package
     * @return \League\Fractal\Resource\Collection
     */
    public function isAddonAvailable(PackagesModel $package){
        return is_null($package->addons()->first())?false:true;
    }

    public function isBonusAvailable(PackagesModel $package){
        return is_null($package->bonus()->first())?false:true;
    }

    public function getsellerName(PackagesModel $package){
        return DB::table('users_profiles')->select('candybrush_users_profiles_name')->where('candybrush_users_profiles_users_id',$package->candybrush_packages_user_id)->first()->candybrush_users_profiles_name;
    }

    public function getFirstPhoto(PackagesModel $package){
        $first_photo=PackagePhoto::where('candybrush_packages_photos_packages_id',$package->id)->first();
        return is_null($first_photo)?NULL:$first_photo->candybrush_packages_photos_url;
    }
    public function getAverageRating(PackagesModel $package){
        return $package->reviews()
            ->selectRaw('avg(candybrush_reviews_rating) as average')->first()->average;
    }
}