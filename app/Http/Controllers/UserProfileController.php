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
use Symfony\Component\HttpFoundation\File\UploadedFile;
class UserProfileController extends BaseController
{
    protected $userProfileTransformer;
    function __construct(UserProfileTransformer $userProfileTransformer)
    {
        $this->userProfileTransformer = $userProfileTransformer;
        // $this->middleware('jwt.auth',['except'=>['authenticate']]);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->response()->collection(User::find($id)->userprofiles()->get(),new UserProfileTransformer());
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
        /**
         * Update profile
         */
       $data = $this->userProfileTransformer->requestAdapter();
        $data=array_filter($data,'strlen'); // filter blank or null array
        if(sizeof($data)){ try{$result=UserProfile::where('candybrush_users_profiles_users_id', $id)->update($data);}catch(\Exception $e){
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

}
