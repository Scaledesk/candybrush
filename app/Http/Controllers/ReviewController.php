<?php

namespace App\Http\Controllers;
use App\libraries\Transformers\ReviewTransformer;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\ReviewModel;

class ReviewController extends BaseController
{

    protected $reviewTransformer;
    function __construct(ReviewTransformer $reviewTransformer)
    {
        $this->reviewTransformer = $reviewTransformer;
        // $this->middleware('jwt.auth',['except'=>['authenticate']]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $data = $this->reviewTransformer->requestAdapter();

        $rules=[
            ReviewModel::USER_ID=>'required',
            ReviewModel::PACKAGE_ID=>'required',
            ReviewModel::COMMENT=>'required',
            ReviewModel::RATING=>'required'
        ];
        $validator=Validator::make($data,$rules,[
            ReviewModel::USER_ID.'.required'=>'The user id is required try user_id=<user_id>',
            ReviewModel::PACKAGE_ID.'.required'=>'The package id is required try package_id=<package_id>',
            ReviewModel::COMMENT.'.required'=>'The comment is required try comment=<comment>',
            ReviewModel::RATING.'.required'=>'The rating is required try rating=<rating>',
        ]);

        if($validator->fails()){
            return $this->error(call_user_func('App\libraries\Messages::showErrorMessages',$validator),422);
        }
        $review=new ReviewModel($data);
        try{
            $review->save();
        }catch(Exception $e){
            return $this->error('some unknown error occurred',520);
        }
        return $this->success();

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
