<?php

/*
*
 * Created by PhpStorm.
 * User: Javed
 * Date: 7/10/15
 * Time: 7:23 PM
 */

namespace App\Http\Controllers;
use App\libraries\Transformers\UserProfileTransformer;
use App\User;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\UserProfile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Symfony\Component\HttpFoundation\File\UploadedFile;
class UserProfileController extends BaseController
{
    protected $userProfileTransformer;
    function __construct(UserProfileTransformer $userProfileTransformer)
    {
        $this->middleware('api.auth');
        $this->userProfileTransformer = $userProfileTransformer;
    }


    /**
     * Display the specified resource.
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function show($id)
    {
        $user=User::find($id);
        return $this->response()->item($user->userprofiles()->first(),new UserProfileTransformer());
    }


    /**
     * Update the specified resource in storage.
     * @return \Illuminate\Http\Response
     * @internal param Request $request
     * @internal param int $id
     */
    public function update()
    {
        /**
         * Update profile
         */
       $data = $this->userProfileTransformer->requestAdapter();
        $data=array_filter($data,'strlen'); // filter blank or null array
        if(sizeof($data)){ 
            try{
                $user_profile=UserProfile::where('candybrush_users_profiles_user_id',Input::get('user_id'))->first();
                $result=$user_profile->update($data);
                }catch(\Exception $e){
            return $this->error($e->getMessage(),$e->getCode());
        }
        }else{
            return $this->error('no adequate field passed',422);
        }
        if($result)
        {
            return $this->success();
        }
        else
        {
            return $this->error('Unknown error',520);
        }
    }
    public function getCommission(Request $request){
        $data=UserProfile::with(array('user'=>function($query){
            $query->select(['id']);
        }))->get(['candybrush_users_profiles_users_id','candybrush_users_profiles_name','candybrush_users_profiles_commission']);
        $data_array=array();
        foreach($data as $row){
        $data_array[]=[
            'user_id'=>$row['user']['id'],
            'name'=>$row['candybrush_users_profiles_name'],
            'commission'=>$row['candybrush_users_profiles_commission']
            ];
        }
        unset($data);
        return response(['data'=>$data_array],200);
    }
    public function setCommission(){
        $user_id=Input::get('user_id','');
        $value=Input::get('commission','');
        $validation_result=$this->my_validate([
           'data'=>[
               'user_id'=>$user_id,
               'value'=>$value
           ],
            'rules'=>[
                'user_id'=>'required|numeric|exists:users,id',
                'value'=>'required|numeric|between:1,100'
            ],
            'messages'=>[
                'user_id.required'=>'User id is required of whom the commission has to be altered try user_id=<user_id>',
                'user_id.numeric'=>'only numbers are allowed as user_id',
                'user_id.exists'=>'User id do not match any records',
                'value.required'=>'Value is required to set the commission try value=<value>',
                'value.numeric'=>'only numbers are allowed as value',
                'value.between'=>'value must be buteen :min and :max'
            ]
        ]);
        if($validation_result['result']){
            $user_profile=User::find($user_id)->userProfiles()->first();

                return DB::transaction(function()use($user_profile,$value){
                    try{
                    $user_profile->update([
                        'candybrush_users_profiles_commission'=>$value
                    ]);
                    return $this->success();}catch(\Exception $e)
                    {
                        return $this->error("sopme unknown error occurred",520);
                    }
                });
        }else{
            return $validation_result['error'];
        }
    }
}
