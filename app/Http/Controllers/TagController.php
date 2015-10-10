<?php

namespace App\Http\Controllers;

use App\libraries\Transformers\TagTransformer;
use App\Tag;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Mockery\CountValidator\Exception;

class TagController extends BaseController
{
    protected $tag_transformer;

    function __construct()
    {
        $this->tag_transformer = new TagTransformer();
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
        $data=array_filter($this->tag_transformer->requestAdaptor(),'strlen');
        $rules=[
            Tag::NAME=>'required|unique:candybrush_tags',
            Tag::DESCRIPTON=>'required',
        ];
        $validator=Validator::make($data,$rules,[
            Tag::NAME.'.required'=>'The name is required try name=<name>',
            Tag::DESCRIPTON.'.required'=>'The description is required try description=<description>',
            Tag::NAME.'.unique'=>'The name already already exist, try different tag name'
        ]);
        $messages=function()use($validator){
            $messages = $validator->messages();
            $errors=[];
            foreach ($messages->all() as $message)
            {
                array_push($errors,$message);
            }
            return $errors;
        };
        if($validator->fails()){
            return $this->error($messages(),422);
        }
        $tag=new Tag($data);
        try{
            $tag->save();
        }catch(Exception $e){
//            parent::report($e);
            return $this->error('some unknown error occurred',520);
        }
        return $this->success();
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
