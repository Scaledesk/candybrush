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
    protected $availableIncludes=['Package'];
    public function transform(PackagePhoto $packagePhoto){
        return[
            'id'=>$packagePhoto[PackagePhoto::ID],
            'url'=>$packagePhoto[PackagePhoto::URL],
        ];
    }
    /*    public function requestAdaptor(){
        return[
            PackagePhoto::URL=>Input::get('url',''),
            PackagePhoto::PACKAGE_ID=>Input::get('package_id',''),
            ];
        }*/
    /**
     * function to take in put from user in array
     * @return array|null
     */
    public function requestAdaptor(){
        $data=array();
        $process_data=Input::get('data','');
        $package_id=Input::get('package_id',NULL);
        if(is_null($package_id)){
            unset($package_id);
            unset($process_data);
            return NULL;
        }
        if($process_data&&is_array($process_data)){
            foreach ($process_data as $package_photos) {
                array_push($data, [
                    PackagePhoto::URL=>Input::get('url',''),
                ]);
            }
            unset($process_data);
            $data['package_id']=$package_id;
            unset($package_id);
            return $data;
        }
        else{
            unset($process_data);
            unset($package_id);
            return NULL;
        }
    }

    /**
     * function to include package
     * @param PackagePhoto $packagePhoto
     * @return \League\Fractal\Resource\Item
     */
    public function includePackage(PackagePhoto $packagePhoto){
        return $this->item($packagePhoto->package(),new \App\libraries\Transformers\PackagesTransformer());
    }
}
