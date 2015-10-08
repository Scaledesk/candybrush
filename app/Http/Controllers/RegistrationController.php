<?php

namespace App\Http\Controllers;

use App\libraries\Transformers\UserTransformer;
use App\User;
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
        //
        $rules=[
            'name'=>'required|min:6|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6'
        ];
        /*$input = Input::only('name',
            'email',
            'password',
            'password_confirmation'
        );*/
      $input=$this->user_transformer->requestAdaptor();
        $validator=Validator::make($input,$rules);
        if($validator->fails()){
             throw new StoreResourceFailedException;
         }
         $confirmation_code= str_random(30);
         //prepare record to enter in database
        $data=[
            'name'=>Input::get('name'),
            'email'=>Input::get('email'),
            'password'=>Hash::make(Input::get('password')),
            'confirmation_code'=>$confirmation_code
        ];
         User::create($data);
        set_time_limit(60); //increase the timeout of php to send mail
        Mail::send('email.verify',array('confirmation_code'=>$confirmation_code), function($message) {
            $message->to(Input::get('email'), Input::get('name'))
                ->subject('Verify your email address');
        });
        return "Thanks for signing up! Please check your email";
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
    public function activateAccount($code){

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
}
