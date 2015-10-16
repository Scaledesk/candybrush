<?php

namespace App\Http\Controllers;

use App\libraries\Messages;
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

    /**
     * success response method with default message and success code
     */
    public function success($message='success',$status_code=200){
        return $this->response()->array([
            'message'=>$message,
            'status_code'=>$status_code
        ])->statusCode($status_code);
    }
    /**
     * error response method with default message and error code
     */
    public function error($message='error',$status_code=404){
        return $this->response()->array([
            'message'=>$message,
            'status_code'=>$status_code
        ])->statusCode($status_code);
    }

    public function my_validate($data_array){
        $validate=Validator::make($data_array['data'],$data_array['rules'],$data_array['messages']);
        if($validate->fails()){
        return ['result'=>false,'error'=>$this->error(Messages::showErrorMessages($validate),422)];
        }else{
            return ['result'=>true,'error'=>'no error'];
        }
    }
}
