<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

use LucaDegasperi\OAuth2Server\Authorizer;
use App\Http\Controllers\S3Provider;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/awsUpload', function()
{
    $s3 = new S3Provider('scaledesk');
    return $s3->access_token();
});




/*
 *  Dingo Api Routes
 */
$api = app('Dingo\Api\Routing\Router');
$api->version('v1', /**
 * @param $api
 */
    function($api){
$api->get('users','App\Http\Controllers\UserController@index');
$api->get('users/{id}', 'App\Http\Controllers\UserController@show');
$api->post('auth/login', function (\Illuminate\Http\Request $request){
    $credentials = $request->only('email', 'password');
    try {
        // verify the credentials and create a token for the user
        if(!(\App\User::where('email','=',$credentials['email'])->get(['confirmed'])[0]['confirmed'])){
            return response()->json(['error'=>'Account not activated'],401);
        }
        if (! $token = \Tymon\JWTAuth\Facades\JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'invalid_credentials'], 401);
        }
    } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
        // something went wrong
        return response()->json(['error' => 'could_not_create_token'], 500);
    }

    // if no errors are encountered we can return a JWT
    $user_id=DB::table('users')->select('id')->where('email',$credentials['email'])->first()->id;
    return response()->json(compact(['token','user_id']));
});
        /**
         * for list all users
         */
        $api->get('user','App\Http\Controllers\UserController@index');
        /**
         * for list specific user
         */
        $api->get('user/{id}','App\Http\Controllers\UserController@show');
    /*
     * for user registration or sign up
     */
  $api->post('signup','App\Http\Controllers\RegistrationController@store');
    /*
     * * for user account activation
     */
    $api->post('users/activate/','App\Http\Controllers\RegistrationController@activateAccount');
    /*
     * * for creating storing user forgot password code
     */
    $api->post('users/forgotPassword/','App\Http\Controllers\RegistrationController@forgotPassword');
    /*
     * for validating the forgot password code i.e. this checks if forgot password code exist in the database
     */
    $api->post('users/validateForgotPasswordCode/','App\Http\Controllers\RegistrationController@validateCode');
    /*
     * for password reset
     */
    $api->post('users/resetPassword/','App\Http\Controllers\RegistrationController@resetPassword');
    /*
     *  for user profile update
     */
    $api->put('userProfile','App\Http\Controllers\UserProfileController@update');
    /*
     * for show user profile
     */
    $api->get('userProfile','App\Http\Controllers\UserProfileController@show');
    /**
     *  for show user portfolio and store portfolio
     */
    $api->get('userPortfolio/{id}','App\Http\Controllers\UserPortfolioController@show');
    $api->post('userPortfolio','App\Http\Controllers\UserPortfolioController@store');

    /***
     * for wallet transactions i.e. credit/debit
     */
    $api->put('users/walletTransaction','App\Http\Controllers\UserwalletController@update');
    /**
     * for create tags
     */
    $api->post('tag','App\Http\Controllers\TagController@store');
    /**
     * for deleting tags
     */
    $api->delete('tag','App\Http\Controllers\TagController@destroy');
    /**
     * for update tags
     */
    $api->put('tag','App\Http\Controllers\TagController@update');
    /**
     *for get all tags
     */
    $api->get('tag','App\Http\Controllers\TagController@index');
    /**
     * for get specific tag
     */
    $api->get('tag/{id}','App\Http\Controllers\TagController@show');
    /**
     * for adding packages
     */
    $api->post('user/packages','App\Http\Controllers\PackagesController@store');
    /**
     * for creating new category
     */
    $api->post('category','App\Http\Controllers\CategoryController@store');
    /**
     * for updating categories
     */
    $api->put('category','App\Http\Controllers\CategoryController@update');
    /**
     * for getting all categories
     */
    $api->get('category','App\Http\Controllers\CategoryController@index');
    /**
     * for getting particular category
     */
    $api->get('category/{id}','App\Http\Controllers\CategoryController@show');
    /**
     *
     * for adding comment and rating package
     */
    $api->post('packages/reviews/','App\Http\Controllers\ReviewController@store');
//    $api->post('packages/reviews','App\Http\Controllers\ReviewController@index');
    $api->get('packages/reviews/adminVerify/{id}','App\Http\Controllers\ReviewController@admin_verified');

    /**
     * for sending message
     */
    $api->post('messages/send','App\Http\Controllers\MessageController@store');
    /**
     * for getting inbox messages
     */
    $api->post('messages/inbox','App\Http\Controllers\MessageController@inbox');
    /**
     * for getting out box messages
     */
    $api->post('messages/sentbox','App\Http\Controllers\MessageController@sentBox');
    /**
     * for deleting  messages
     */
    $api->post('messages/delete/{id}','App\Http\Controllers\MessageController@destroy');
        /**
         * get Inbox messages
         */
    $api->get('user/{id}/inbox/','App\Http\Controllers\UserController@getInboxMessages');
    /**
    * get Sent box messages
    */
    $api->get('user/{id}/sentMessages/','App\Http\Controllers\UserController@getSentMessages');
        /**
         * for deleting messages
         */
        $api->delete('message/{id}','App\Http\Controllers\MessageController@destroy');
        /**
         * add addon to the package
         */
        $api->post('addon','App\Http\Controllers\AddonController@store');
        /**
         * delete addon
         */
        $api->delete('addon/{id}','App\Http\Controllers\AddonController@destroy');
        /**
         * show all packages
         */
        $api->get('package','App\Http\Controllers\PackagesController@index');
        /**
         * show specific package
         */
        $api->get('package/{id}','App\Http\Controllers\PackagesController@show');
        /**
         * add bonus to the package
         */
        $api->post('bonus','App\Http\Controllers\BonusController@store');
        /**
         * delete bonus
         */
        $api->delete('bonus/{id}','App\Http\Controllers\BonusController@destroy');
        /**
         * for updating package
         */
        $api->put('user/packages/{id}','App\Http\Controllers\PackagesController@update');
        /**
         * for setComplete the package and send for review
         */
        $api->put('user/completePackage/{id}','App\Http\Controllers\PackagesController@setCompleted');
        /**
         * for deleting package review
         */
        $api->delete('user/{user_id}/packages/{package_id}','App\Http\Controllers\PackagesController@destroy');
        /**
         * for new booking
         */
        $api->post('booking','App\Http\Controllers\BookingController@store');
        /**
         * for get all bookings
         */
        $api->get('booking','App\Http\Controllers\BookingController@index');
        /**
         * for get specific booking
         */
        $api->get('booking/{id}','App\Http\Controllers\BookingController@show');
        /**
         * for updating booking
         */
        $api->put('booking/{id}','App\Http\Controllers\BookingController@update');
        /**
         * for confirming the pament
         */
        $api->put('confirmBooking/{id}','App\Http\Controllers\BookingController@confirmPaymentStatus');
        /**
         * insert Request Feature
         */
        $api->post('requestFeature','App\Http\Controllers\RequestFeatureController@store');
        /**
         * get all Request Feature
         */
        $api->get('requestFeature','App\Http\Controllers\RequestFeatureController@index');
        /**
         * get specific Request Feature
         */
        $api->get('requestFeature/{id}','App\Http\Controllers\RequestFeatureController@show');
        /**
         * for delete requestFeature
         */
        $api->delete('requestFeature/{id}','App\Http\Controllers\RequestFeatureController@destroy');
        /**
         * for delete package by admin
         */
        $api->delete('adminDeletePackage/{id}','App\Http\Controllers\PackagesController@adminDestroyPackage');
        /**
         * for searching the packages
         */
        $api->get('packages','App\Http\Controllers\PackagesController@index');
        /**
         * get all user commission
         */
        $api->get('commission','App\Http\Controllers\UserProfileController@getCommission');
        /**
         * set user commission
         */
        $api->post('commission/','App\Http\Controllers\UserProfileController@setCommission');
        /**
         * insert new badge
         */
        $api->post('badge/','App\Http\Controllers\BadgeController@store');
        /**
         * get all badge
         */
        $api->get('badge/','App\Http\Controllers\BadgeController@index');
        /**
         * get specific badge
         */
        $api->get('badge/{id}','App\Http\Controllers\BadgeController@show');
        /**
         * give user a badge
         */
        $api->post('user/{user_id}/badge/{badge_id}','App\Http\Controllers\BadgeController@giveUserABadge');
        /**
         * insert notification
         */
        $api->post('notification/','App\Http\Controllers\NotificationController@store');
        /**
         * get all notification
         */
        $api->get('notification/','App\Http\Controllers\NotificationController@index');
        /**
         * get specific notification
         */
        $api->get('notification/{id}','App\Http\Controllers\NotificationController@show');
        /**
         * delete specific notification
         */
        $api->delete('notification/{id}','App\Http\Controllers\NotificationController@destroy');
        /**
         * set seen notification
         */
        $api->put('notification/{id}','App\Http\Controllers\NotificationController@update');
        /**
         * add referral
         */
        $api->post('referral/','App\Http\Controllers\ReferralController@store');
        /**
         * for Referral Sign up
         */
        $api->post('referralSignUp/{code}','App\Http\Controllers\ReferralController@referralSignUp');
        /**
         * get all referrals
         */
        $api->get('referral','App\Http\Controllers\ReferralController@index');
        /**
         *for documentation
         */
        $api->get('documentation/',function(){
            $swagger = new \Swagger\Swagger(app_path());
            header('Content-Type: application/json');
            print_r(json_encode($swagger->getResourceList()));
            die;
            echo $swagger->getResource($swagger->getResourceList(),array('output' => 'json'));
        });
        //for google login
        $api->post('auth/google','App\Http\Controllers\Auth\AuthController@google');
        //for facebook login
        $api->post('auth/facebook','App\Http\Controllers\Auth\AuthController@facebook');
        /**
         * add package photos in bulk
         */
        $api->post('packagePhoto','App\Http\Controllers\PackagePhotoController@store');
        /**
         * get package photos
         */
        $api->get('packagePhoto/{package_id}','App\Http\Controllers\PackagePhotoController@index');
        /**
         * get specific package photo
         */
        $api->get('specificPackagePhoto/{photo_id}','App\Http\Controllers\PackagePhotoController@show');

        /**
         * admin routes
         */
        /**
         * user count
         */
        $api->get('admin/userCount','App\Http\Controllers\UserController@userCount');
        /**
         * package count
         */
        $api->get('admin/packageCount','App\Http\Controllers\PackagesController@packageCount');
        /**
         * order count
         */
        $api->get('admin/bookingCount','App\Http\Controllers\BookingController@bookingCount');
        /**
         * for delete category
         */
        $api->delete('admin/category/{id}','App\Http\Controllers\CategoryController@destroy');
        /**
         * for activate and  inactivate user
         */
        $api->put('admin/users/changeStatus','App\Http\Controllers\UserController@update');
    });

/*
 * OAuth2 Server Routes
 */
Route::get('oauth/authorize', ['as' => 'oauth.authorize.get','middleware' => ['check-authorization-params', 'auth'], function() {
    // display a form where the user can authorize the client to access it's data
    $authParams = Authorizer::getAuthCodeRequestParams();
    $formParams = array_except($authParams,'client');
    $formParams['client_id'] = $authParams['client']->getId();
    return View::make('oauth.authorization-form', ['params'=>$formParams,'client'=>$authParams['client']]);
}]);
Route::post('oauth/authorize', ['as' => 'oauth.authorize.post','middleware' => ['csrf', 'check-authorization-params', 'auth'], function() {

    $params = Authorizer::getAuthCodeRequestParams();
    $params['user_id'] = Auth::user()->id;
    $redirectUri = '';

    // if the user has allowed the client to access its data, redirect back to the client with an auth code
    if(Input::get('approve') !== null) {
        $redirectUri = Authorizer::issueAuthCode('user', $params['user_id'], $params);
    }

    // if the user has denied the client to access its data, redirect back to the client with an error message
    if (Input::get('deny') !== null) {
        $redirectUri = Authorizer::authCodeRequestDeniedRedirectUri();
    }
    return Redirect::to($redirectUri);
}]);
Route::post('oauth/access_token', function() {
    return Response::json(Authorizer::issueAccessToken());
});
Route::controller('auth', 'Auth\AuthController');