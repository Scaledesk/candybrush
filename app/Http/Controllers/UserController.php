<?php

namespace App\Http\Controllers;

use App\libraries\Transformers\MessageTransformer;
use App\libraries\Transformers\SentMessagesTransformer;
use App\libraries\Transformers\UserTransformer;
use App\Message;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Mockery\CountValidator\Exception;
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
            if($user=User::where('id',$user_id)->first()){
                try{
                    User::where('id',$user_id)->update(['confirmed'=>1,'confirmation_code'=>Null]);
                }catch(Exception $e){
                    return $this->error('unknown error occoured',520);
                }
                return $this->success();
            }else{
                return $this->error('user_id do not match any records',404);
            }
        };
        $deactivate=function(){
            $user_id=Input::get(strtolower('user_id'),'');
            if($user_id==''){
                return $this->error('User_id not provided! Try user_id=<user_id>',422);
            }
            if($user=User::where('id',$user_id)->first()){
                try{
                    User::where('id',$user_id)->update(array('confirmed'=>0));
                }catch(Exception $e){
                    return $this->error('unknown error occoured',520);
                }
                    return $this->success();
            }else{
                return $this->error('user_id do not match any records',404);
            }
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

    /**
     *return inbox messages
     * @param $id
     * @return
     */
    public function getInboxMessages($id){
         $validation_result=$this->my_validate([
            'data'=>[
                'user_id'=>$id
            ],
            'rules'=>[
            'user_id'=>'required|exists:users,id|numeric',
            ],
            'messages'=>[
                'user_id.required'=>'user_id is required pass user_id in url as user/{id}/inbox/',
                'user_id.exists'=>'user_id do not match any records, try with different user_id',
                'user_id.numeric'=>'Only numbers are requires as id',
            ]
        ]);
        if($validation_result['result']){
            //do
         /*   $user=User::find($id);
            $messages=$user->recieversMessage;*/
           /* print_r($messages);
            die;*/
            /*return $this->response()->collection($messages,new MessageTransformer());*/
            /*return response($messages);*/
            $messages_id=DB::table('candybrush_messages_receivers')->where('candybrush_messages_recievers_user_id','=',$id)->get(['candybrush_messages_recievers_message_id']);
            $messages_id1=[];
            foreach($messages_id as $id){
                array_push($messages_id1,$id->candybrush_messages_recievers_message_id);
            }
            $messages_id=$messages_id1;
            unset($messages_id1);
            $messages=Message::whereIn('id',$messages_id)->get();
            return $this->response()->collection($messages,new MessageTransformer());
        }else{
            return $validation_result['error'];
        }
    }
    /**
     *return sent messages
     */
    public function getSentMessages($id){
        $validation_result=$this->my_validate([
            'data'=>[
                'user_id'=>$id
            ],
            'rules'=>[
                'user_id'=>'required|exists:users,id|numeric',
            ],
            'messages'=>[
                'user_id.required'=>'user_id is required pass user_id in url as user/{id}/sentMessages/',
                'user_id.exists'=>'user_id do not match any records, try with different user_id',
                'user_id.numeric'=>'Only numbers are requires as id',
            ]
        ]);
        if($validation_result['result']){
           $message=Message::where('candybrush_messages_user_id','=',$id)->get();
            return $this->response()->collection($message,new SentMessagesTransformer());
        }else{
            return $validation_result['error'];
        }
    }
}
