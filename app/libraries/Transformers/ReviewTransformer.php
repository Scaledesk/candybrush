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
            'rating'=>$review->candybrush_reviews_rating,
            'comment'=>$review->candybrush_reviews_comment,
            'user_id'=>$review->candybrush_reviews_user_id
            ];
    }
    public function requestAdapter()
    {
        return [
            ReviewModel::USER_ID => Input::get('user_id'),
            ReviewModel::PACKAGE_ID => Input::get('package_id'),
            ReviewModel::RATING => Input::get('rating'),
            ReviewModel::COMMENT => Input::get('comment')
        ];
    }
}