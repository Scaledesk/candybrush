<?php
/**
 * Created by PhpStorm.
 * User: tushar
 * Date: 5/11/15
 * Time: 2:40 PM
 */

namespace app\libraries\Transformers;


use App\PackagePhoto;
use Illuminate\Support\Facades\Input;
use League\Fractal\TransformerAbstract;

class PackagePhotoTransformer extends TransformerAbstract{
    public function transform(){
        return[
            
        ];
    }
    public function requestAdaptor(PackagePhoto $packagePhoto){
    return[
        PackagePhoto::URL=>Input::get('url',''),
        PackagePhoto::PACKAGE_ID=>Input::get('package_id',''),
        ];
    }
}