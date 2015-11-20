<?php
/**
 * Created by PhpStorm.
 * User: Javed
 * Date: 7/10/15
 * Time: 7:23 PM
 */
namespace App\libraries\Transformers;
use App\PackagesModel;
use App\Addon;
use App\libraries\Transformers\AddonTransformer;
use Dingo\Api\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use League\Fractal\TransformerAbstract;

class PackagesTransformer extends TransformerAbstract{
    protected $defaultIncludes = ['Addons','Bonus','seller','category','tags','photos','reviews'];
    public function transform(PackagesModel $package){
        return [
            'id'=>$package->id,
            'name'=>$package->candybrush_packages_name,
            'description'=>$package->candybrush_packages_description,
         /*   'sub_category'=>$package->candybrush_packages_sub_category,*/
            'seller_name'=>self::getsellerName($package),
            'price'=>(integer)$package->candybrush_packages_price,
            'deal_price'=>(integer)$package->candybrush_packages_deal_price,
            'available_date'=>$package->candybrush_packages_available_date,
            'term_condition'=>$package->candybrush_packages_term_condition,
            'payment_type'=>$package->candybrush_packages_payment_type,
            'maximum_delivery_days'=>$package->candybrush_packages_maximum_delivery_days,
            'status'=>$package->candybrush_packages_status,
            'instructions'=>$package->candybrush_packages_instructions,
            'location'=>$package->candybrush_packages_location,
            'average_rating'=>self::getAverageRating($package),
            'meeting_availability'=>$package->candybrush_packages_meeting_availability,
            'meeting_address'=>$package->candybrush_packages_meeting_address
             ];
    }
    public function requestAdapter()
    {
        return [
            PackagesModel::NAME => Input::get('name',''),
            PackagesModel::DESCRIPTION => Input::get('description',''),
            PackagesModel::CATEGORY_ID => Input::get('category_id',''),
            PackagesModel::TAG_ID=>Input::get('tags_id',''),
            /*PackagesModel::SUB_CATEGORY => Input::get('sub_category',''),*/
            PackagesModel::PRICE => Input::get('price',''),
            PackagesModel::DEAL_PRICE => Input::get('deal_price',''),
            PackagesModel::AVAILABLE_DATE => Input::get('available_date',''),
            PackagesModel::TERM_CONDITION => Input::get('term_condition',''),
            PackagesModel::PAYMENT_TYPE => Input::get('payment_type',''),
            PackagesModel::MAXIMUM_DELIVERY_DAYS => Input::get('maximum_delivery_days',''),
            PackagesModel::INSTRUCTIONS=>Input::get('instructions',''),
            PackagesModel::LOCATION=>Input::get('location',''),
            /*PackagesModel::User_ID=>Input::get('user_id',''),*/
            /*PackagesModel::STATUS=>Input::get('status','')*/
            'addons'=>Input::get('addons',''),
            'bonus'=>Input::get('bonus',''),
            'photos'=>Input::get('photos',''),
            'installments'=>Input::get('installments',''),
            PackagesModel::MEETING_AVAILABILITY=>Input::get('meeting_availability',''),
            PackagesModel::MEETING_ADDRESS=>Input::get('meeting_address',''),

        ];
    }

    /**
     * for including Addons
     * @param PackagesModel $package
     * @return \League\Fractal\Resource\Collection
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

    public function includeCategory(PackagesModel $package){
        return $this->item($package->category()->first(),new CategoryTransformer());
    }
    public function includeTags(PackagesModel $package){
        return $this->collection($package->tags()->get(),new TagTransformer());
    }
    public function includePhotos(PackagesModel $package){
        return $this->collection($package->photos()->get(),new PackagePhotoTransformer());
    }
    public function getsellerName(PackagesModel $package){
        return DB::table('users_profiles')->select('candybrush_users_profiles_name')->where('candybrush_users_profiles_users_id',$package->candybrush_packages_user_id)->first()->candybrush_users_profiles_name;
    }
    public function includeReviews(PackagesModel $package){
        return $this->collection($package->reviews()->get(),new ReviewTransformer());
    }
    public function getAverageRating(PackagesModel $package){
        return $package->reviews()
            ->selectRaw('avg(candybrush_reviews_rating) as average')->first()->average;
    }
}