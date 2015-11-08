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
use League\Fractal\TransformerAbstract;
use App\libraries\Transformers\PackagePhotoTransformer;

class FirstTimePackageTransformer extends TransformerAbstract{
    public function transform(PackagesModel $package){
        return [
            'id'=>$package->id,
            'name'=>$package->candybrush_packages_name,
            'price'=>(integer)$package->candybrush_packages_price,
            'deal_price'=>(integer)$package->candybrush_packages_deal_price,
            'addon_available'=>self::isAddonAvailable($package),
            'bonus_available'=>self::isBonusAvailable($package),
            'seller_name'=>self::getsellerName($package),
            'first_photo'=>self::getFirstPhoto($package)
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
        return $package->seller()->first()->name;
    }

    public function getFirstPhoto(PackagesModel $package){
        $first_photo=PackagePhoto::where('candybrush_packages_photos_packages_id',$package->id)->first();
        return is_null($first_photo)?NULL:$first_photo->candybrush_packages_photos_url;




    }
}