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
        return $this->response()->collection(User::find($id)->userPortfolio()->get(),new UserPortfolioTransformer());
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

        $portfolio = UserPortfolio::create($data);

        print_r($portfolio);
        die;



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
