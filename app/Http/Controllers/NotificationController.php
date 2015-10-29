<?php

namespace App\Http\Controllers;

use App\libraries\Transformers\NotificationTransformer;
use App\Notification;
use App\User;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class NotificationController extends BaseController
{
    protected $notification_transformer;

    function __construct()
    {
        $this->notification_transformer=new NotificationTransformer();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->has('user_id')){
            if(!is_numeric($request->get('user_id'))){
                return $this->error('only numbers are allowerd as user_id');
            }
            $user=User::find($request->get('user_id'));
            if(is_null($user)){
                return $this->error('User_id do not match any records',404);
            }
            return $this->response()->collection($user->notifications()->get(),$this->notification_transformer);
        }
        return $this->response()->collection(Notification::all(),$this->notification_transformer);
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
        $data=$this->notification_transformer->requestAdaptor();
        $validation_result=$this->my_validate([
            'data'=>$data,
            'rules'=>[
                Notification::TEXT=>'required',
                Notification::TYPE=>'required|in:'.implode(',',Notification::types()),
                Notification::USER_ID=>'required|numeric|exists:users,id'
            ],
            'messages'=>[
                Notification::USER_ID.'.required'=>'user id is required try user_id=<user id>',
                Notification::USER_ID.'.numeric'=>'only numbers are allowed in user_id',
                Notification::USER_ID.'.exists'=>'user id do not match any records',
                Notification::TEXT.'.required'=>'text is required try text=<your text>',
                Notification::TYPE.'.required'=>'Notification types are required try type=<notification type>',
                Notification::TYPE.'.in'=>'only from these values :'.implode(',',Notification::types())
            ]
        ]);
        if($validation_result['result']){
            $do_insert=function()use($data){
                try{
                    $notification=new Notification($data);
                    $notification->save();
                }catch(\Exception $e){
                   return $this->error('some unknown error occurred',520);
                }
                return $this->success();
            };
            return DB::transaction(function()use($do_insert){
                return $do_insert();
                }
            );

        }else{
            return $validation_result['error'];
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
