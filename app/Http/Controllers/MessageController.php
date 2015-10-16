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
            Message::RECIEVER_ID.'.required'=>'Required Array of receivers try recievers_id=<array of one or more receivers id>',
            Message::RECIEVER_ID.'.min:1'=>'minimum one receiver in receiver array is required'
        ];
        $validate=$this->my_validate(['data'=>$data,'rules'=>$rules,'messages'=>$messages]);
        if($validate['result']){
            //do
            $user=User::find($data[Message::USER_ID]);
            $message=new Message($data);
            $user->messages()->save($message);
            $message->recieverUsers()->attach($data[Message::RECIEVER_ID]);
            return $this->success();
        }else {
            //not done
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
        //
    }
}
