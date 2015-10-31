<?php

namespace App\Http\Controllers;

use App\libraries\Transformers\UserTransformer;
use App\User;
use App\UserWallet;
use Dingo\Api\Exception\StoreResourceFailedException;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use League\Fractal\Resource\Item;


class RegistrationController extends BaseController
{
    private $user_transformer;

    function __construct()
    {
        $this->user_transformer = new UserTransformer();
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
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $input=$this->user_transformer->requestAdaptor();
        $validate_result=$this->my_validate([
            'data'=>$input,
            'rules'=>[
                'name'=>'required|min:6|unique:users',
                'email' => 'required|email|unique:users',
                'password' => 'required|confirmed|min:6'
            ],
            'messages'=>[
                'name.required'=>'Name is required try name=<name>',
                'email.required'=>'Email is required try email=<email>',
                'password.required'=>'Password is required try password=<password>',
                'password.confirmed'=>'Password_confirmation do not match try password_confirmation=<retype passward>',
                'name.unique'=>'Name already exists. Try with different name',
                'email.unique'=>'Email already exists. Try with different email',
                'name.min'=>'Minimum six characters must be required in name',
                'password.min'=>'Minimum six characters must be required in password'
            ]
        ]);
        if($validate_result['result']){
            $confirmation_code= str_random(30);
            DB::transaction(function()use($confirmation_code) {

                //prepare record to enter in database
                $data=[
                    'name'=>Input::get('name'),
                    'email'=>Input::get('email'),
                    'password'=>Hash::make(Input::get('password')),
                    'confirmation_code'=>$confirmation_code
                ];
                $user= User::create($data);
                $user->userProfiles()->create(['candybrush_users_profiles_users_id'=>$user->id]);
                $user->userWallet()->create(['candybrush_users_wallet_user_id'=>$user->id,'candybrush_users_wallet_amount'=>0]);
                DB::table('candybrush_users_wallet_transactions')->insert([
                    'candybrush_users_wallet_transactions_wallet_id'=>$user->id,
                    'candybrush_users_wallet_transactions_description'=>'Create wallet with 0 credit',
                    'candybrush_users_wallet_transactions_type'=>'credit',
                    'candybrush_users_wallet_transactions_amount'=>0]);
            });
            set_time_limit(60); //increase the timeout of php to send mail
            Mail::send('email.verify',array('confirmation_code'=>$confirmation_code), function($message) {
                $message->to(Input::get('email'), Input::get('name'))
                    ->subject('Verify your email address');
            });
            return $this->success('Thanks for signing up! Please check your email');
        }else{
            return $validate_result['error'];
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
    /*
     * function to activate account
     */
    public function activateAccount(){
        $code=Input::get('confirmation_code','');
        if($code==''){
            return $this->error('Code not provided, try confirmation_code=<your code>',422);
        }
        if($value=DB::table('users')->where('confirmation_code', $code)->value('confirmation_code')){
            if(DB::table('users')->where('confirmation_code', $code)->update(['confirmed'=>1,'confirmation_code'=>NULL])){
                return  $this->success();
            }else{
                return $this->response()->error('Try Again! unknown error occoured',520);
            }
        }else{
            return $this->error('Code expires');
        }
    }
    /*
     * function to insert code for forgot password
     */
    public function forgotPassword(){
        $email=Input::get('email','');
        if($email==''){
            return $this->error('Email not provided, try email=<your email>',422);
        }
        $code='';
        // check if email exist in database
        if(User::where('email','=',$email)->first(['id'])){
            $code= str_random(30); // make random code
            User::where('email','=',$email)->update(['forgot_password_code'=>$code]); //code store in database
        }else{
            return $this->error('Email does not match any records',404); // error message on email not found
        }
        try {
            set_time_limit(60); //increase the timeout of php to send mail
            Mail::send('email.ForgotPassword', array('forgot_password_code' => $code), function ($message) {
                $message->to(Input::get('email'))
                    ->subject('ForgotPassword');
            });
        }catch(\Exception $e){
            return $this->response()->array($e);
        }
        return $this->success('Success!Email also sent');
    }
    /*
     * function to validate the forgot password code
     */
    public function validateCode(){
        $code=Input::get('forgot_password_code','');
        if($code==''){
            return $this->error('Code not provided, try forgot_password_code=<your code>',422);
        }
        if(User::where('forgot_password_code','=',$code)->first(['id'])){
            return $this->success();
        }else{
            return $this->error('code expired');
        }
    }
    /*
     * function to reset the password
     */
    public function resetPassword(){
        $str='';
        $code=Input::get('forgot_password_code','');
        $password=Input::get('password','');
        $password_confirmation=Input::get('password_confirmation','');
        $check_for_password_match=true;
        if($code==''){
            $str.='Code not provided, try forgot_password_code=<your code>';
        }
        if($password==''){
            $str.='<br/>password not provided, try password=<your password><br/>';
            $check_for_password_match=false;
        }
        if($password_confirmation==''){
            $str.='<br/>password_confirmation not provided, try password_confirmation=<your confirm password><br/>';
            $check_for_password_match=false;
        }
        if($check_for_password_match){
            if($password!=$password_confirmation){
                $str.='<br/>password and password_confirmation do not match<br/>';
            }
        }
        if($str!=''){
            return $this->error($str,422);
        }
        $rules=[
            'password' => 'required|confirmed|min:6'
        ];
        $validator=Validator::make([
            'password'=>$password,
            'password_confirmation'=>$password_confirmation
        ],$rules);
        if($validator->fails()){
            return $this->error('error! check password length must be greater than 6',422);
        }
        if(User::where('forgot_password_code','=',$code)->update([
            'password'=>Hash::make($password),
            'forgot_password_code'=>NULL
        ])){
            return $this->success();
        }else{
            if(User::where('forgot_password_code','=',$code)->first(['forgot_password_code'])){
                return $this->error('unknown error occurred! Try Again',520);
            }
            else{
                return $this->error('code expired');
            }
        }
    }
}
