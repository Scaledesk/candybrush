<?php
/**
 * Created by PhpStorm.
 * User: Javed
 * Date: 7/10/15
 * Time: 7:23 PM
 */
namespace App\libraries\Transformers;
use App\ReviewModel;
use Dingo\Api\Http\Request;
use Illuminate\Support\Facades\Input;
use League\Fractal\TransformerAbstract;

class ReviewTransformer extends TransformerAbstract{

    public function transform(ReviewModel $review){
        return [
            'seller_communication_rating'=>$review->candybrush_reviews_seller_communication_rating,
            'seller_as_described_rating'=>$review->candybrush_reviews_seller_as_described,
            'would_recommend_rating'=>$review->candybrush_reviews_would_recommend,
            'average_rating'=>$review->candybrush_reviews_rating,
            'comment'=>$review->candybrush_reviews_comment,
            'user_id'=>$review->candybrush_reviews_user_id
            ];
    }
    public function requestAdapter()
    {
        return [
            ReviewModel::SELLER_AS_DESCRIBED_RATING=>Input::get('seller_as_described_rating'),
            ReviewModel::SELLER_COMMUNICATION_RATING=>Input::get('seller_communication_rating'),
            ReviewModel::WOULD_RECONMMEND_RATING=>Input::get('would_recommend_rating'),
            ReviewModel::USER_ID => Input::get('user_id'),
            ReviewModel::PACKAGE_ID => Input::get('package_id'),
            ReviewModel::COMMENT => Input::get('comment'),
            ReviewModel::ADMIN_VERIFIED => 0
        ];
    }
}