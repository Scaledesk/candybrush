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
use App\UserProfile;
use Illuminate\Support\Facades\DB;
use League\Fractal\TransformerAbstract;
use App\libraries\Transformers\PackagePhotoTransformer;
use App\libraries\Transformers\CategoryTransformer;
use App\libraries\Transformers\UserProfileTransformer;
use App\libraries\Transformers\AddonTransformer;
use App\libraries\Transformers\BonusTransformer;
use App\libraries\Transformers\UserTransformer;
use App\libraries\Transformers\TagTransformer;
use App\libraries\Transformers\ReviewTransformer;

class FirstTimePackageTransformer extends TransformerAbstract{
    protected $availableIncludes = ['category'];
    protected $defaultIncludes = ['Addons','Bonus','seller','category','tags','photos','reviews'];
    public function transform(PackagesModel $package){
        return [
            'id'=>$package->id,
            'name'=>$package->candybrush_packages_name,
            'seller_name'=>self::getsellerName($package),
            'price'=>(integer)$package->candybrush_packages_price,
            'deal_price'=>(integer)$package->candybrush_packages_deal_price,
            'average_rating'=>$package->candybrush_packages_average_rating,
            'addon_available'=>self::isAddonAvailable($package),
            'bonus_available'=>self::isBonusAvailable($package),
            'first_photo'=>self::getFirstPhoto($package),
            /*'maximum_delivery_days'=>$package->candybrush_packages_maximum_delivery_days,*/
            'delivery_time'=>$package->candybrush_packages_delivery_time,
            'delivery_time_type'=>$package->candybrush_packages_delivery_time_type,
            'location'=>$package->candybrush_packages_location,

            /**
             * extra fields from packages transformer
             * may be removed in future from this transformer
             */
            'description'=>$package->candybrush_packages_description,
            'available_date'=>$package->candybrush_packages_available_date,
            'term_condition'=>$package->candybrush_packages_term_condition,
            'payment_type'=>$package->candybrush_packages_payment_type,
            /*'maximum_delivery_days'=>$package->candybrush_packages_maximum_delivery_days,*/
            'status'=>$package->candybrush_packages_status,
            'instructions'=>$package->candybrush_packages_instructions,
            'meeting_availability'=>$package->candybrush_packages_meeting_availability,
            'meeting_address'=>$package->candybrush_packages_meeting_address,
            "seller_profile"=>self::getSellerProfile($package),
            "timestamp" => $package->updated_at
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
    public function includeCategory(PackagesModel $package){
        return $this->item($package->category()->first(),new CategoryTransformer());
    }

    /**
     * extra functions from packages transformer
     * may be removed in future from this transformer
     */
    public function includeAddons(PackagesModel $package){
        return $this->collection($package->addons()->get(),new AddonTransformer());
    }

    public function includeBonus(PackagesModel $package){
        return $this->collection($package->bonus()->get(),new BonusTransformer());
    }
    public function includeSeller(PackagesModel $package){
        return $this->item($package->seller()->first(),new UserTransformer());
    }
    public function includeTags(PackagesModel $package){
        return $this->collection($package->tags()->get(),new TagTransformer());
    }
    public function includePhotos(PackagesModel $package){
        return $this->collection($package->photos()->get(),new PackagePhotoTransformer());
    }
    public function getSellerProfile(PackagesModel $package){
        $user_profile=UserProfile::where('candybrush_users_profiles_users_id',$package->candybrush_packages_user_id)->first();
        $transformer=new UserProfileTransformer();
        return $transformer->transform($user_profile);
    }
    public function includeReviews(PackagesModel $package){
        return $this->collection($package->reviews()->get(),new ReviewTransformer());
    }
}