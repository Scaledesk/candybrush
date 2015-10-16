<?php

namespace App\Http\Controllers;

use app\libraries\Messages;
use App\libraries\Transformers\MessageTransformer;
use App\Message;
use App\User;
use Faker\Provider\Base;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class MessageController extends BaseController
{
    protected $message_transformer;

    function __construct()
    {
        $this->message_transformer=new MessageTransformer();
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
        $data=$this->message_transformer->requestAdaptor();
        $rules=[
            Message::USER_ID=>'required|exists:users,id|numeric',
            Message::BODY=>'required',
            Message::SUBJECT=>'required',
            Message::RECIEVER_ID=>'required|min:1'
        ];
        $messages=[
            Message::BODY.'.required'=>'Body of message is required try body=<body>',
            Message::SUBJECT.'.required'=>'subject of message is required try subject=<subject>',
            Message::USER_ID.'.exists'=>'user_id do not match any records! try with different user_id',
            Message::USER_ID.'.required'=>'user_id is required try user_id=<user_id>',
            Message::USER_ID.'.numeric'=>'Only numbers are allowed in user_id',
            Message::RECIEVER_ID.'.required'=>'Required Array of receivers try receivers_id=<array of one or more receivers id>',
            Message::RECIEVER_ID.'.min:1'=>'minimum one receiver in receiver array is required'
        ];
        $validate=$this->my_validate(['data'=>$data,'rules'=>$rules,'messages'=>$messages]);
        if($validate['result']){
            if(!is_array($data[Message::RECIEVER_ID])){
                return $this->error('receivers_id must be in array try receivers_id=[1,2,3]',422);
            }
            $receivers_id=$data[Message::RECIEVER_ID];
            $data[Message::RECIEVER_ID]=implode(',',$receivers_id);
            $user=User::find($data[Message::USER_ID]);
            $message=new Message($data);
            $user->messages()->save($message);
            $message->recieverUsers()->attach($receivers_id);
            unset($receivers_id);
            return $this->success();
        }else {
            return $validate['error'];
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
    $delete_type=strtolower(Input::get('delete_type',''));
        $user_id=Input::get('user_id','');
        $validation_result=$this->my_validate([
            'data'=>[
                'user_id'=>$user_id,
                'delete_type'=>$delete_type,
                'message_id'=>$id
            ],
            'rules'=>[
                'user_id'=>'required|exists:users,id',
                'delete_type'=>'required|in:inbox,sent',
                'message_id'=>'required|exists:candybrush_messages_receivers,candybrush_messages_recievers_message_id|numeric'
            ],
            'messages'=>[
                'user_id.required'=>'user_id is required try user_id=<user_id>',
                'user_id.exists'=>'user_id do not match any records! try with different user_id',
                'delete_type.required'=>'delete_type of message is required try delete_type=<inbox or sent>',
                'delete_type.in'=>'wrong delete type only inbox and sent is allowed as input to delete',
                'message_id.required'=>'message_id is required try in url as message/<message id>',
                'message_id.exists'=>'message_id do not match any records! try with different message_id',
                'message_id.numeric'=>'only numbers are allowed as message_id',
            ]
        ]);
        if($validation_result['result']){
            $deleteFromInbox=function($user_id,$id){
                $user=User::find($user_id);
                $message=$user->recieversMessage()->where('candybrush_messages_recievers_message_id','=',$id)->first();
                if(is_null($message)){
                    return $this->error('Tyring to access wrong message! User not have this message');
                }else{
                    $deleted=$user->recieversMessage()->where('candybrush_messages_recievers_message_id','=',$id)->detach($id);
                    if($deleted<1){
                        return $this->error('unknown error occurred',520);
                    }else{
                        return $this->success();
                    }
                }
            };
            $deleteSentMessages =function($user_id,$id){
                $user=User::find($user_id);
                $message=$user->messages()->where('id','=',$id)->first();
                if(is_null($message)){
                    return $this->error('Tyring to access wrong message! User not have this message');
                }else{
                    if($message->delete()<1){
                        return $this->error('unknown error occurred',520);
                    }else{
                        return $this->success();
                    }
                }
            };
        if($delete_type=="inbox"){
        return $deleteFromInbox($user_id,$id);
            }else{
            return $deleteSentMessages($user_id,$id);
            }
    }else {
            return $validation_result['error'];
        }
    }
}
