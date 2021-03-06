<?php

namespace App\Http\Controllers;

use App\Badge;
use App\libraries\Transformers\BadgeTransformer;
use App\User;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class BadgeController extends BaseController
{
    protected $badge_transformer;

    function __construct()
    {
        $this->badge_transformer= new BadgeTransformer();
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
            return $this->response()->collection($user->badges()->get(),$this->badge_transformer);
        }
        return $this->response()->collection(Badge::all(),$this->badge_transformer);
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
        $data=$this->badge_transformer->requestAdaptor();
        $validation_result=$this->my_validate([
                'data'=>$data,
                'rules'=>[
                    Badge::NAME=>'required|unique:'.Badge::TABLE.','.Badge::NAME,
                    Badge::IMAGE_URL=>'required'
                ],
                'messages'=>[
                    Badge::NAME.'.required'=>'name of badge is required try name=<name of badge>',
                    Badge::NAME.'.unique'=>'name of badge has already been taken, try with different name',
                    Badge::IMAGE_URL.'.required'=>'image of url is required try image_url=<url of image>'
                ]
            ]);
        if($validation_result['result']){
            //do something with badge
            $badge=new Badge($data);
            $save_badge=function()use($badge){
                if($badge->save()){
                    return $this->success();
                }else{
                    $this->error('some error occurred!',520);
                }
            };
            $result=DB::transaction($save_badge);
            unset($save_badge,$badge,$validation_result);
            return $result;
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
        $data=[
            'badge_id'=>$id
        ];
        $validation_result=$this->my_validate([
                'data'=>$data,
                'rules'=>[
                    'badge_id'=>'required|numeric|exists:'.Badge::TABLE.','.Badge::ID
                ],
                'messages'=>[
                    'badge_id.required'=>'badge id is required, give id in url badge\<badge_id>',
                    'badge_id.numeric'=>'only numbers are allowed in badge_id',
                    'badge_id.exists'=>'badge id do not match any records.',
                ]
        ]);
        if($validation_result['result']){
        return $this->response()->item(Badge::find($id),$this->badge_transformer);
    }else{
            return $validation_result['error'];
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

    public function giveUserABadge($user_id=null,$badge_id=null){
        $data=[
          'user_id'=>$user_id,
            'badge_id'=>$badge_id
        ];
        $validation_result=$this->my_validate([
            'data'=>$data,
            'rules'=>[
                'user_id'=>'required|numeric|exists:users,id',
                'badge_id'=>'required|numeric|exists:'.Badge::TABLE.','.Badge::ID
            ],
            'messages'=>[
                'badge_id.required'=>'badge id is required, give id in url badge\<badge_id>',
                'badge_id.numeric'=>'only numbers are allowed in badge_id',
                'badge_id.exists'=>'badge id do not match any records.',
                'user_id.required'=>'user id is required, give id in url user\<user_id>',
                'user_id.numeric'=>'only numbers are allowed in user_id',
                'user_id.exists'=>'user id do not match any records',
            ]
        ]);
        if($validation_result['result']){
            //do something with given data
            unset($data);
            $user_not_has_this_badge=function()use($badge_id,$user_id){
                $badge=Badge::find($badge_id);
                $user=$badge->usersById($user_id)->first();
                return is_null($user)?true:false;
            };
            $badge=Badge::find($badge_id);
            if($user_not_has_this_badge()){
                    $do_attach=function()use($badge,$user_id){
                        try
                            {
                                $badge->users()->attach($user_id);
                                return $this->success();
                            }catch(\Exception $e ){
                            return $this->error('some unknown error occurred',520);
                        }
                };
                $result=DB::transaction($do_attach);
                unset($user_not_has_this_badge,$badge,$do_attach,$user_id,$badge_id);
                return $result;
            }else{
                return $this->error('user already has this badge',401);
            }

        }else{
            return $validation_result['error'];
        }
    }
}
