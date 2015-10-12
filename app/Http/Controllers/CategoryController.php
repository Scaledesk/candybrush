<?php

namespace App\Http\Controllers;

use App\Category;
use App\libraries\Transformers\CategoryTransformer;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CategoryController extends BaseController
{
    protected $category_transformer;

    function __construct()
    {
        $this->category_transformer=new CategoryTransformer();
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
     * @return \Illuminate\Http\Response
     * @internal param Request $request
     */
    public function store()
    {
        //
        $data=$this->category_transformer->requestAdaptor();
        $validator=Validator::make($data,[
            Category::NAME=>'required',
            Category::PARENT_ID=>'required',
        ],
            [
            Category::Name.'.required'=>'Category name is required try name=<name>',
            Category::PARENT_ID.'.required'=>'parent id is required try parent_id=<parent id>',
        ]);
        if($validator->passes()){

        }else{
            return $this->error(call_user_func('App\libraries\Messages::showErrorMessages',$validator),422);
        }
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
