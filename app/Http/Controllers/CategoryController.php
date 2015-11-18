<?php

namespace App\Http\Controllers;

use App\Category;
use App\libraries\Transformers\CategoryTransformer;
use App\Tag;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Mockery\CountValidator\Exception;

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
        return $this->response()->collection(Category::all(),$this->category_transformer);
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
            Category::NAME.'.required'=>'Category name is required try name=<name>',
            Category::PARENT_ID.'.required'=>'parent id is required try parent_id=<parent id> or parent_id=none in case of no parent',
        ]);
        if($validator->passes()){
            $insert=function($data){
                $category=new Category($data);
                return  $category->save()?$this->success():$this->error('unknown error occurred',520);
            };
            if($data[Category::PARENT_ID]=='none')
            {
                $data[Category::PARENT_ID]=NULL;
               return $insert($data);
            }
            elseif(Category::where('candybrush_categories_id','=',$data[Category::PARENT_ID])->exists()){
                return $insert($data);
            }else{
                return $this->error('parent_id not exists! Try with another parent id',404);
            }
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
        $category=Category::find($id);
        if(is_null($category)){
            return $this->error('record not found');
        }else{
            return $this->response()->item($category,$this->category_transformer);
        }
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
     * Up
     * date the specified resource in storage.
     * @return \Illuminate\Http\Response
     * @internal param Request $request
     * @internal param int $id
     */
    public function update()
    {
        //
        $id=Input::get('id',NULL);
        if(is_null($id)){
            return $this->error('Category Id required try id=<id>',422);
        }
        $validator=Validator::make(['id'=>$id],[
            'id'=>'numeric'
        ],
            [
                'id.numeric'=>'only numbers are allowed as id',
            ]);
        if($validator->fails()){
            return $this->error(call_user_func('App\libraries\Messages::showErrorMessages',$validator),422);
        }
        if(Category::where('candybrush_categories_id','=',$id)->exists()){ //check for record need to update
            $update=function($data)use($id){
                try{Category::where('candybrush_categories_id','=',$id)->update($data);
                   return $this->success();
                }catch(Exception $e){
                    return $this->error('unknown error occurred',520);
                }
            };
            $data=$this->category_transformer->requestAdaptor();
            $data=array_filter($data,'strlen');
            $validator=Validator::make($data,[
                Category::NAME=>'required',
                Category::PARENT_ID=>'required|numeric',
            ],
                [
                    Category::NAME.'.required'=>'Category name is required try name=<name>',
                    Category::PARENT_ID.'.required'=>'parent id is required try parent_id=<parent id> or parent_id=none in case of no parent',
                    Category::PARENT_ID.'.numeric'=>'only numbers are allowed as parent id'
                ]);
            if($validator->passes()){
                if($data[Category::PARENT_ID]=='none')
                {
                    $data[Category::PARENT_ID]=NULL;
                    return $update($data);
                }
                elseif(Category::where('candybrush_categories_id','=',$data[Category::PARENT_ID])->exists()){ // check for the parent if exist
                    return $update($data);
                }else{
                    return $this->error('parent_id not exists! Try with another parent id',404);
                }
            }else{
                return $this->error(call_user_func('App\libraries\Messages::showErrorMessages',$validator),422);
            }
        }else{
            return $this->error('id do not match any records! Try with different id.',404);
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
        $category=Category::find($id);
        if(is_null($category)){
            return $this->error('record not found');
        }else{
            $array=DB::table('candybrush_packages')->where('candybrush_packages_category_id',$category->candybrush_categories_id)->select(['id'])->get();
            $array=json_decode(json_encode($array), true);
            if(count($array)>0){
                /*return $this->error('please delete packages first',422);*/
                return $this->errorWithData("please delete packages first","422",['packages_id'=>$array]);
            }
            $category->delete();
            return $this->success();
        }
    }
}
