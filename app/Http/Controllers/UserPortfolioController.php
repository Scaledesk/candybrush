<?php
namespace App\Http\Controllers;
use App\libraries\Transformers\UserPortfolioTransformer;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\UserPortfolio;
use App\User;
class UserPortfolioController extends BaseController
{
    protected $userPortfolioTransformer;
    function __construct(UserPortfolioTransformer $userPortfolioTransformer)
    {
        $this->userPortfolioTransformer = $userPortfolioTransformer;
        // $this->middleware('jwt.auth',['except'=>['authenticate']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        return $this->response()->collection(UserPortfolio::where('candybrush_users_portfolio_user_id', $id)->get(), new UserPortfolioTransformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /**
         *  store user port folio
         */


        $data = $this->userPortfolioTransformer->requestAdapter();
        try{
            $portfolio = UserPortfolio::create($data);
        }
        catch(\Exception $e){
            return $this->error($e->getMessage(),$e->getCode());
        }

        if(!$portfolio)
        {
            return $this->error();
        }
        else{
            return $this->success();
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
