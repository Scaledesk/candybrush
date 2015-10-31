<?php

namespace App\Http\Controllers;

use App\libraries\Transformers\ReferralTransformer;
use App\libraries\Transformers\UserTransformer;
use App\Referral;
use App\User;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;

class ReferralController extends BaseController
{
    protected $referral_transformer;

    /**
     * ReferralController constructor.
     */
    public function __construct()
    {
        $this->referral_transformer=new ReferralTransformer();
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
        $data=$this->referral_transformer->requestAdaptorForReferring();
        $validation_result=$this->my_validate([
            'data'=>$data,
            'rules'=>[
                Referral::USER_ID=>'required|numeric|exists:users,id',
                Referral::REFFERED_USER_EMAIL=>'required|email',
                Referral::REFERRAL_CODE=>'required'
            ],
            'messages'=>[
                Referral::USER_ID.'.required'=>'user id is required, give id in url user\<user_id>',
                Referral::USER_ID.'.numeric'=>'only numbers are allowed in user_id',
                Referral::USER_ID.'.exists'=>'user id do not match any records',
                Referral::REFFERED_USER_EMAIL.'.required'=>'email of whow you want to reffer is required try referred_user_email=<valid email id>',
                Referral::REFFERED_USER_EMAIL.'.email'=>'email must be a valid email id',
                Referral::REFERRAL_CODE.'.required'=>' some error occurred we are unable to generate that one referral code is required, try again'
            ]
        ]);
        if($validation_result['result']){
            $referral = new Referral($data);
           /* print_r($referral);
            die;*/
            $result=DB::transaction(function()use($referral){
                try{
                    $referral->save();
                }catch(\Exception $e){
                  /*  print_r($e->getMessage());
                    die;*/
                    return false;
                }
                return true;
            });
            if($result){
                try{
                    set_time_limit(60); //increase the timeout of php to send mail
                    Mail::send('email.Referral',array('referral_code'=>$data[Referral::REFERRAL_CODE]), function($message)use($data) {
                        $message->to($data[Referral::REFFERED_USER_EMAIL], $data[Referral::REFFERED_USER_EMAIL])
                            ->subject('You have been referred');
                    });
                }catch(\Exception $e){
                    return $this->error('email was not send, some error occurred, please try again',520);
                }
            return $this->success();
            }else{
                return $this->error('Some unknown error occurred try again',520);
            }
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
    public function referralSignUp($referral_code){
        if(is_null($referral_code)){
            return $this->error('referral code is required',422);
        }
        $referral=Referral::where(Referral::REFERRAL_CODE,$referral_code)->first();
            $validateCode =function()use($referral_code,$referral){
                if(is_null($referral)){
                return false;
                }
                //return true if only no user id in referred_users_id in referral table corresponding record
                if(is_null($referral->candybrush_referrals_referred_users_id)){
                    return true;
                }return false;
            };
            if($validateCode($referral_code)){
                //do sign up
                $do_referral_signup=function()use($referral){
                    $user_transformer=new UserTransformer();
                    $input=$user_transformer->requestAdaptor();
                    $validate_result=$this->my_validate([
                        'data'=>$input,
                        'rules'=>[
                            'name'=>'required|min:6|unique:users',
                            'password' => 'required|confirmed|min:6'
                        ],
                        'messages'=>[
                            'name.required'=>'Name is required try name=<name>',
                            'password.required'=>'Password is required try password=<password>',
                            'password.confirmed'=>'Password_confirmation do not match try password_confirmation=<retype passward>',
                            'name.unique'=>'Name already exists. Try with different name',
                            'name.min'=>'Minimum six characters must be required in name',
                            'password.min'=>'Minimum six characters must be required in password'
                        ]
                    ]);
                    if($validate_result['result']){
                        /*$confirmation_code= str_random(30);*/
                        if(!is_null(User::where('email',$referral->candybrush_referrals_referred_users_email)->first())){
                            return $this->error("User already registered with this email id");
                        }
                        DB::transaction(function()use($referral){
                            //prepare record to enter in database
                            $data=[
                                'name'=>Input::get('name'),
                                'email'=>$referral->candybrush_referrals_referred_users_email,
                                'password'=>Hash::make(Input::get('password')),
                                'confirmation_code'=>NULL,
                                'confirmed'=>'1'
                            ];
                            $user= User::create($data);
                            $user->userProfiles()->create(['candybrush_users_profiles_users_id'=>$user->id]);
                            $user->userWallet()->create(['candybrush_users_wallet_user_id'=>$user->id,'candybrush_users_wallet_amount'=>0]);
                            DB::table('candybrush_users_wallet_transactions')->insert([
                                'candybrush_users_wallet_transactions_wallet_id'=>$user->id,
                                'candybrush_users_wallet_transactions_description'=>'Create wallet with 0 credit',
                                'candybrush_users_wallet_transactions_type'=>'credit',
                                'candybrush_users_wallet_transactions_amount'=>0]);
                            $referral->candybrush_referrals_referred_users_id=$user->id;
                            $referral->save();
                        });
                        set_time_limit(60); //increase the timeout of php to send mail
                        Mail::send('email.ThankYouSignUp',array('name'=>$input['name']), function($message)use($input,$referral) {
                            $message->to($referral->candybrush_referrals_referred_users_email, $input['name'])
                                ->subject('Acknowledgement');
                        });
                        return $this->success('Thanks for signing up! Enjoy our services');
                    }else{
                        return $validate_result['error'];
                    }
                };
                return $do_referral_signup();
                    }else{
                return $this->error("Referral code expired",520);
                }
    }
}
