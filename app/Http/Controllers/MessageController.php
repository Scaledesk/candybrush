<?php
namespace App\Http\Controllers;
use App\libraries\Transformers\MessageTransformer;
use App\MessagesModel;
use App\MessagesUserModel;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class MessageController extends BaseController
{

    protected $messageTransformer;
    function __construct(MessageTransformer $messageTransformer)
    {
        $this->messageTransformer = $messageTransformer;
        // $this->middleware('jwt.auth',['except'=>['authenticate']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function inbox()
    {
        $user_id = Input::get('user_id');
        /*$inbox = MessagesUserModel::with('messagesModel')->with('user')->where('candybrush_messages_user_id','=', $user_id)
            ->where('candybrush_messages_message_type', 'In')->get();*/
        /*print_r($inbox);*/
        $inbox = MessagesUserModel::with('messagesModel')->where('candybrush_messages_user_id','=', $user_id)
            ->where('candybrush_messages_message_type', 'In')->get();
        $this->response()->collection($inbox,$this->messageTransformer);
    }
    /**
     * function for getting all sent box messages
     */
    public function sentBox()
    {
        $user_id = Input::get('user_id');
        $sent_box = MessagesUserModel::with('messagesModel')->with('user')->where('candybrush_messages_user_id','=', $user_id)
            ->where('candybrush_messages_message_type', 'Out')->get();
        print_r($sent_box);
        die;

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
        // storing messages
        $data = $this->messageTransformer->requestAdapter();
        $result = MessagesModel::create($data);
        $receiver = $request->receiver;
        $sender = $request->sender;
        if($sender!='') {
            $data1 = [
                'candybrush_messages_user_id' => $sender,
                'candybrush_messages_message_id' => $result->id,
                'candybrush_messages_message_type' => 'Out'
            ];
            $result->messagesUserModel()->create($data1);
        }
        if($receiver!='') {
            $data2 = [
                'candybrush_messages_user_id' => $receiver,
                'candybrush_messages_message_id' => $result->id,
                'candybrush_messages_message_type' => 'In'
            ];
            $result->messagesUserModel()->create($data2);
        }
        return $this->success();


        /*$rules=[


        ];
        $validator=Validator::make($data,$rules,[

        ]);*/
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
        // delete messages
        if(MessagesUserModel::destroy($id))
        {
            return $this->success();
        }
        else{
            return $this->error();
        }
    }
}
