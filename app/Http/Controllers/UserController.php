<?php

namespace App\Http\Controllers;

use App\libraries\Transformers\UserTransformer;
use App\User;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Monolog\Handler\NullHandlerTest;

class UserController extends BaseController
{
    function __construct()
    {
        //$this->middleware('jwt.auth',['except'=>['authenticate']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // all users
        return $this->response()->collection(User::all(),new UserTransformer());
        /*$users=User::paginate(1);
        return $this->response()->paginator($users,new UserTransformer());*/
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
        return $this->response()->item(User::findOrFail($id),new UserTransformer());
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
    public function update()
    {
        //
        $activate=function(){
            $user_id=Input::get(strtolower('user_id'),'');
            if($user_id==''){
                return $this->error('User_id not provided! Try user_id=<user_id>',422);
            }
            return User::find($user_id)->update(['confirmed'=>1])?$this->success():$this->error('unknown error occoured',520);
        };
        $deactivate=function(){
            $user_id=Input::get(strtolower('user_id'),'');
            if($user_id==''){
                return $this->error('User_id not provided! Try user_id=<user_id>',422);
            }
            return User::find($user_id)->update(['confirmed'=>'0'])?$this->success():$this->error('unknown error occoured',520);
        };
        switch(Input::get('todo','')){
            case 'activate':{
                return $activate();
                break;
            }
            case 'deactivate':{
                return $deactivate();
                break;
            }
            default : return $this->error('unknown action requested try todo=<activate/deactivate>',422);
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
