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
            'user_id'=>$portfolio->candybrush_users_portfolio_user_id,
            'portfolio_description'=>$portfolio->candybrush_users_portfolio_description,
            'portfolio_file'=>$portfolio->candybrush_users_portfolio_file,
            'portfolio_file_type'=>$portfolio->candybrush_users_portfolio_file_type
             ];
    }
    public function requestAdapter()
    {
        return [
            UserPortfolio::USER_ID => Input::get('user_id'),
            UserPortfolio::PORTFOLIO_DESCRIPTION => Input::get('portfolio_description'),
            UserPortfolio::PORTFOLIO_FILE => Input::get('portfolio_file'),
            UserPortfolio::PORTFOLIO_FILE_TYPE => Input::get('portfolio_file_type')
        ];
    }
}