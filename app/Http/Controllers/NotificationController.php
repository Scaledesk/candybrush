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
        $check_seen=function()use($request){
            if(is_numeric($request->get('seen'))&&$request->get('seen')==0||$request->get('seen')==1){
                return true;
            }
            return false;
        };
        if($request->has('user_id')){
            if(!is_numeric($request->get('user_id'))){
                return $this->error('only numbers are allowerd as user_id',422);
            }
            $user=User::find($request->get('user_id'));
            if(is_null($user)){
                return $this->error('User_id do not match any records',404);
            }
            if($request->has('seen')){
                if($check_seen()) {
                    return $this->response()->collection($user->notifications()->where('candybrush_notifications_seen', '=', $request->get('seen'))->get(), $this->notification_transformer);
                }else{
                    return $this->error('only 0 or 1 allowed as seen',422);
                }
            }
            return $this->response()->collection($user->notifications()->get(),$this->notification_transformer);
        }else if($request->has('seen')){
            if($check_seen()) {
                return $this->response()->collection(Notification::where('candybrush_notifications_seen', '=', $request->get('seen'))->where('candybrush_notifications_user_id',$this->auth()->user()->id)->get(), $this->notification_transformer);
            }else{
                return $this->error('only 0 or 1 allowed as seen',422);
            }
        }
        return $this->response()->collection(Notification::where('candybrush_notifications_user_id',$this->auth()->user()->id)->get(),$this->notification_transformer);
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
            if(is_numeric($id)){
                $notification=Notification::find($id);
                if(is_null($notification)){
                    return $this->error('Notification id do not match any records',404);
                }
                return $this->response()->item($notification,$this->notification_transformer);
            }else{
                return $this->error('Only numbers are allowed as notification_id',422);
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
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     * @internal param Request $request
     */
    public function update($id)
    {
        $do_seen=function($notification){
            $notification->candybrush_notifications_seen=1;
            $notification->save();
            return $this->success('Read notification successfully');
        };
        if(is_numeric($id)){
            $notification=Notification::find($id);
            if(is_null($notification)){
                return $this->error('Notification id do not match any records',404);
            }
            return DB::transaction(function()use($do_seen,$notification){
                return $do_seen($notification);
            });
        }else{
            return $this->error('Only numbers are allowed as notification_id',422);
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
        $do_delete=function($notification){
          $notification->delete();
            return $this->success();
        };
        if(is_numeric($id)){
            $notification=Notification::find($id);
            if(is_null($notification)){
                return $this->error('Notification id do not match any records',404);
            }
            return DB::transaction(function()use($do_delete,$notification){
                return $do_delete($notification);
            });
        }else{
            return $this->error('Only numbers are allowed as notification_id',422);
        }
    }
}
