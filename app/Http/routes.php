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

Route::get('/', function () {
    return view('welcome');
});
/*
 *  Dingo Api Routes
 */
$api = app('Dingo\Api\Routing\Router');
$api->version('v1',function($api){
$api->get('users','App\Http\Controllers\UserController@index');
$api->get('users/{id}', 'App\Http\Controllers\UserController@show');
$api->post('auth/login', function (\Illuminate\Http\Request $request){
    $credentials = $request->only('email', 'password');
    try {
        // verify the credentials and create a token for the user
        if (! $token = \Tymon\JWTAuth\Facades\JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'invalid_credentials'], 401);
        }
    } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
        // something went wrong
        return response()->json(['error' => 'could_not_create_token'], 500);
    }

    // if no errors are encountered we can return a JWT
    return response()->json(compact('token'));
});
  $api->post('signup','App\Http\Controllers\RegistrationController@store');
    $api->post('users/activate/{code}','App\Http\Controllers\UserController@activateAccount');
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
    if (Input::get('approve') !== null) {
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
Route::resource("userProfiles", "UserProfileController");