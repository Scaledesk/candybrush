<?php

namespace App\Http\Controllers;

use App\libraries\Messages;
use App\libraries\Transformers\TagTransformer;
use App\Tag;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
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
        return $this->response()->collection(Tag::all(),$this->tag_transformer);
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
        if($validator->fails()){
            return $this->error(call_user_func('App\libraries\Messages::showErrorMessages',$validator),422);
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
     * @return \Illuminate\Http\Response
     * @internal param Request $request
     * @internal param int $id
     */
    public function update()
    {
        $id = Input::get('id','');
        if($id==''){
            return $this->error('Tag Id nopt provided try id=<id>');
        }
        $data=$this->tag_transformer->requestAdaptor();
        $validator=Validator::make($data,[
            Tag::NAME=>'required|unique:candybrush_tags',
            Tag::DESCRIPTON=>'required',
        ],[
            Tag::NAME.'.required'=>'The name is required try name=<name>',
            Tag::DESCRIPTON.'.required'=>'The description is required try description=<description>',
            Tag::NAME.'.unique'=>'The name already already exist, try different tag name'
        ]);
        if($validator->passes()){
            try{
                Tag::find($id)->update($data);
            }catch(Exception $e){
                return $this->error('some unknown error occurred',520);
            }
        }else{
            return $this->error(call_user_func('App\libraries\Messages::showErrorMessages',$validator),422);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function destroy()
    {
        //
        $data=[Tag::ID=>Input::get('id')];
        $rules=[
            Tag::ID=>'required',

        ];
        $validator=Validator::make($data,$rules,[
            Tag::ID.'.required'=>'The id is required try id=<id>',
        ]);
        if($validator->fails()){
            return $this->error(call_user_func('App\libraries\Messages::showErrorMessages',$validator),422);
        }
        if($tag=Tag::where(['candybrush_tags_id'=>$data[Tag::ID]])->first()){
            try{
                Tag::destroy($data);
            }catch(Exception $e){
                return $this->error('some unknown error occoured',520);
            }
        }else{
            return $this->error('id do not match any records',404);
        }
        return $this->success();
    }
}
