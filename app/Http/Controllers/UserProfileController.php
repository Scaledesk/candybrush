<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use App\UserProfile;

class UserProfileController extends Controller
{
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
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // store user profile in database
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
        return $profile;
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
        //dd($request->all());
        $data = $request->all();

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

    public function getdata()
    {

        return [
            UserProfile::CANDYBRUSH_USERS_PROFILES_FIRST_NAME => Input::get(UserProfile::CANDYBRUSH_USERS_PROFILES_FIRST_NAME),
            UserProfile::CANDYBRUSH_USERS_PROFILES_LAST_NAME => Input::get(UserProfile::CANDYBRUSH_USERS_PROFILES_LAST_NAME),
            UserProfile::CANDYBRUSH_USERS_PROFILES_MOBILE => Input::get(UserProfile::CANDYBRUSH_USERS_PROFILES_MOBILE),
            UserProfile::CANDYBRUSH_USERS_PROFILES_ADDRESS => Input::get(UserProfile::CANDYBRUSH_USERS_PROFILES_ADDRESS),
            UserProfile::CANDYBRUSH_USERS_PROFILES_STATE => Input::get(UserProfile::CANDYBRUSH_USERS_PROFILES_STATE),
            UserProfile::CANDYBRUSH_USERS_PROFILES_CITY => Input::get(UserProfile::CANDYBRUSH_USERS_PROFILES_CITY),
            UserProfile::CANDYBRUSH_USERS_PROFILES_PIN => Input::get(UserProfile::CANDYBRUSH_USERS_PROFILES_PIN)

        ];
    }
}
