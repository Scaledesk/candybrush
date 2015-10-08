<?php

namespace App\Http\Controllers;

use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class BaseController extends Controller
{
        use Helpers;
    /*
     * success response method with default message and success code
     */
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
}
