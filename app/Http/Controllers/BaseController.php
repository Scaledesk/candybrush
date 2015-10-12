<?php

namespace App\Http\Controllers;

use app\libraries\Messages;
use Dingo\Api\Routing\Helpers;
use Illuminate\Validation\Factory;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class BaseController extends Controller
{
        use Helpers;
    /*
     * success response method with default message and success code
     */
    /*function __construct()
    {
    $validator=parent::getValidatorInstance();
        $validator->extend('validateExistInDatabase',function($attribute,$value,$parameters){
            print_r($attribute);
            echo '<br/>';
            print_r($value);
            echo '<br/>';
            print_r($parameters);
            echo 1;
            die;
        });
//         $validator->after(function($attribute,$value,$parameters){
//            print_r($attribute);
//            echo '<br/>';
//            print_r($value);
//            echo '<br/>';
//            print_r($parameters);
//            echo 1;
//            die;
//        });
    }*/
    public function success($message='success',$status_code=200){
        return $this->response()->array([
            'message'=>$message,
            'status_code'=>$status_code
        ])->statusCode($status_code);
    }
    /*
     * * error response method with default message and error code
     */
    public function error($message='error',$status_code=404){
        return $this->response()->array([
            'message'=>$message,
            'status_code'=>$status_code
        ])->statusCode($status_code);
    }

//    protected static function myValidate($data_array,$rules,$messages, $error_code){
//        $validator=Validator::make($data_array,$rules,$messages);
//        if($validator->passes()){
//            return true;
//        }else{
//            return self::error(call_user_func('App\libraries\Messages::showErrorMessages',$validator),$error_code);
//        }
//    }
}
