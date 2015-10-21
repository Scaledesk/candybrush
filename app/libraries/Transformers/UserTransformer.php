<?php
/**
 * Created by PhpStorm.
 * User: tushar
 * Date: 7/10/15
 * Time: 7:23 PM
 */

namespace App\libraries\Transformers;


use App\User;
use Illuminate\Support\Facades\Input;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract{
    protected $availableIncludes=['RequestFeatures','Packages'];
    public function transform(User $user){
        return [
            'id'  =>$user->id,
            'name'=>$user->name,
            'email'=>$user->email
        ];
    }
    public function requestAdaptor(){
        return[
            'name'=>Input::get('name'),
            'email'=>Input::get('email'),
            'password'=>Input::get('password'),
            'password_confirmation'=>Input::get('password_confirmation')
        ];
    }
    public function includeRequestFeatures(User $user){
        return $this->collection($user->requestFeatures()->get(),new RequestFeatureTransformer());
    }

    public function includePackages(User $user){
        return $this->collection($user->packages()->get(),new PackagesTransformer());
    }
}