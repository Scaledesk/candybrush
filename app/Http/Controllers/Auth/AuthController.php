<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Laravel\Socialite\Facades\Socialite;
use Tymon\JWTAuth\JWTAuth;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends BaseController
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    public function google(Request $request)
    {
        try {
            //Google oAuth2 Code
            if ($request->has('redirectUri')) {
                config()->set("services.google.redirect", $request->get('redirectUri'));
            }
            $provider = Socialite::driver('google');
            $provider->stateless();

            // Step 1 + 2
            $profile = $provider->user();

            $email=$profile->email;
            $name=$profile->name;
            $user=User::where('email',$email)->first();
            if(is_null($user)){
                DB::transaction(function()use($email,$name){
                    //prepare record to enter in database
                    $data=[
                        'name'=>$email,
                        'email'=>$email,
                        'password'=>Null,
                        'confirmation_code'=>NULL,
                        'confirmed'=>'1'
                    ];
                    $user= User::create($data);
                    $user->userProfiles()->create(['candybrush_users_profiles_users_id'=>$user->id,
                        'candybrush_users_profiles_name'=>$name]);
                    $user->userWallet()->create(['candybrush_users_wallet_user_id'=>$user->id,'candybrush_users_wallet_amount'=>0]);
                    DB::table('candybrush_users_wallet_transactions')->insert([
                        'candybrush_users_wallet_transactions_wallet_id'=>$user->id,
                        'candybrush_users_wallet_transactions_description'=>'Create wallet with 0 credit',
                        'candybrush_users_wallet_transactions_type'=>'credit',
                        'candybrush_users_wallet_transactions_amount'=>0]);
                });
                $user=User::where('email',$email)->first();
                if(is_null($user)){
                    return $this->error('some error occurred try again',520);
                }
                set_time_limit(60); //increase the timeout of php to send mail
                Mail::send('email.ThankYouSignUp',array('name'=>$name), function($message)use($name,$email) {
                    $message->to($email, $name)
                        ->subject('Acknowledgement');
                });
                /**
                 * get token without authentication
                 */
                $token=\Tymon\JWTAuth\Facades\JWTAuth::fromUser($user);
                return Response::json(['token' => $token]);
            }else{
                $token=\Tymon\JWTAuth\Facades\JWTAuth::fromUser($user);
                return Response::json(['token' => $token]);
            }
        } catch(\Exception $e) {
            echo $e->getMessage();
            return $this->error('Some Error Occurred!',520);
        }

    }

    public function facebook(Request $request)
    {
        try {
            //facebook oAuth2 Code
            if ($request->has('redirectUri')) {
                config()->set("services.facebook.redirect", $request->get('redirectUri'));
            }
            $provider = Socialite::driver('facebook');
            $provider->stateless();

            // Step 1 + 2
            $profile = $provider->user();

            $email=$profile->email;
            $name=$profile->name;
            $user=User::where('email',$email)->first();
            if(is_null($user)){
                DB::transaction(function()use($email,$name){
                    //prepare record to enter in database
                    $data=[
                        'name'=>$email,
                        'email'=>$email,
                        'password'=>Null,
                        'confirmation_code'=>NULL,
                        'confirmed'=>'1'
                    ];
                    $user= User::create($data);
                    $user->userProfiles()->create(['candybrush_users_profiles_users_id'=>$user->id,
                        'candybrush_users_profiles_name'=>$name]);
                    $user->userWallet()->create(['candybrush_users_wallet_user_id'=>$user->id,'candybrush_users_wallet_amount'=>0]);
                    DB::table('candybrush_users_wallet_transactions')->insert([
                        'candybrush_users_wallet_transactions_wallet_id'=>$user->id,
                        'candybrush_users_wallet_transactions_description'=>'Create wallet with 0 credit',
                        'candybrush_users_wallet_transactions_type'=>'credit',
                        'candybrush_users_wallet_transactions_amount'=>0]);
                });
                $user=User::where('email',$email)->first();
                if(is_null($user)){
                    return $this->error('some error occurred try again',520);
                }
                set_time_limit(60); //increase the timeout of php to send mail
                Mail::send('email.ThankYouSignUp',array('name'=>$name), function($message)use($name,$email) {
                    $message->to($email, $name)
                        ->subject('Acknowledgement');
                });
                /**
                 * get token without authentication
                 */
                $token=\Tymon\JWTAuth\Facades\JWTAuth::fromUser($user);
                return Response::json(['token' => $token]);
            }else{
                $token=\Tymon\JWTAuth\Facades\JWTAuth::fromUser($user);
                return Response::json(['token' => $token]);
            }
        } catch(\Exception $e) {
            echo $e->getMessage();
            return $this->error('Some Error Occurred!',520);
        }

    }
}
