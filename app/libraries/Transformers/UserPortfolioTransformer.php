<?php
/**
 * Created by PhpStorm.
 * User: Javed
 * Date: 9/10/15
 * Time: 6:23 PM
 */
namespace App\libraries\Transformers;
use App\UserPortfolio;
use App\UserProfile;
use Dingo\Api\Http\Request;
use Illuminate\Support\Facades\Input;
use League\Fractal\TransformerAbstract;

class UserPortfolioTransformer extends TransformerAbstract{

    public function transform(UserPortfolio $portfolio){
        return [
            'name'=>$portfolio->candybrush_users_id,
            'mobile'=>$portfolio->candybrush_users_portfolio_description,
            'address'=>$portfolio->candybrush_users_portfolio_file
             ];
    }
    public function requestAdapter()
    {
        return [
            UserPortfolio::USER_ID => Input::get('user_id'),
            UserPortfolio::DESCRIPTION => Input::get('portfolio_description'),
            UserPortfolio::FILE => Input::get('portfolio_file'),
        ];
    }
}