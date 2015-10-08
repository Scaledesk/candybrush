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
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
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
        // show profile data
        $profile = UserProfile::where('candybrush_users_profiles_users_id', $id)->get();
        return $this->response()->item($profile,new UserProfileTransformer());

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

        /*//print_r($request->file());
        $file = $request->file();
        print_r($file);
        die;
       */
        $data = $request->all();
        //$data = $this->userProfileTransformer->requestAdapter();
        UserProfile::where('candybrush_users_profiles_users_id', $id)->update($data);


        //$data = $this->getdata();
        /*$profile = UserProfile::find($id);
        $profile->update($data);*/
        echo "success";
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
