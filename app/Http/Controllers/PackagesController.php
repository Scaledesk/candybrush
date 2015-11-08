<?php

namespace App\Http\Controllers;

use App\Category;
use App\libraries\Transformers\PackagesTransformer;
use App\PackegesUserModel;
use App\Tag;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\PackagesModel;
use App\libraries\Constants;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use League\Fractal\Manager;
use Mockery\CountValidator\Exception;
use Psy\Command\ListCommand\Enumerator;

class PackagesController extends BaseController
{
    /**
     * creating constructor
     */
    protected $packageTransformer;
    function __construct()
    {
        $this->packageTransformer = new PackagesTransformer();
        // $this->middleware('jwt.auth',['except'=>['authenticate']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   // get packages on basis of status
        //fuction for doing eager loading
        $do_eager_loading=function(){
            $fractal = new Manager();
            $fractal->setSerializer(new \League\Fractal\Serializer\ArraySerializer());

            $requestedIncludes = Input::get('include');

            if(!is_array($requestedIncludes))
                $requestedIncludes = array($requestedIncludes);

            $availableRequestedIncludes = array_intersect($this->packageTransformer->getAvailableIncludes(), $requestedIncludes);
            $defaultIncludes = $this->packageTransformer->getDefaultIncludes();

            $includes = array_merge($availableRequestedIncludes, $defaultIncludes);

            $eagerLoads = array();

            foreach ($includes as $includeKey => $includeName) {
                if (gettype($includeKey) === "string") {
                    unset($includes[$includeKey]);
                    array_push($eagerLoads, $includeKey);
                } else {
                    array_push($eagerLoads, $includeName);
                }
            }
        };
        //get packages on the basis of status
        if($request->has('status')){
            $do_eager_loading();
            return $this->response()->collection(PackagesModel::where("candybrush_packages_status",$request->status)->get(),$this->packageTransformer);
        }
        //get packages on the basis of category
        if($request->has('category_id')){
            $do_eager_loading();
            if(is_null($category=Category::find($request->get('category_id')))){
                return $this->error('Sorry the category_id do not match any records', 404);
            }
            return $this->response()->collection($category->packages()->get(),$this->packageTransformer);
        }
        //full text search to packages
       if($request->has('query')) {
           $packages = PackagesModel::with(['tags','category','bonus','addons','seller'])->search(
               Input::get('query', '')
           )->get()->unique();
           $do_eager_loading();
           return $this->response()->collection($packages, $this->packageTransformer);
       }
        //return all packages
        $do_eager_loading();
        $packages=PackagesModel::paginate(15)->appends(['orderBy'=>'id']);
//        $packages = DB::table('candybrush_packages')->orderby('id','desc')->paginate(6);
        return $this->response()->paginator($packages,$this->packageTransformer);
//        return response($packages,200);
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
        $u_id = $this->auth()->user()->id;
        $data = $this->packageTransformer->requestAdapter();
        $data[PackagesModel::User_ID]=$u_id;
        unset($u_id);
        $data=array_filter($data,'strlen'); // filter blank or null array
        $validation_result=$this->my_validate([
            'data'=>$data,
            'rules'=>[
                PackagesModel::NAME=>'required',
                PackagesModel::DESCRIPTION=>'required',
                PackagesModel::User_ID=>'required|exists:users,id',
                PackagesModel::CATEGORY_ID=>'required|min:1|exists:candybrush_categories,candybrush_categories_id'
            ],
            'messages'=>[
                PackagesModel::NAME.'.required'=>'Name of package is required, try name=<name>',
                PackagesModel::DESCRIPTION.'.required|Name of package is required, try description=<description>',
                PackagesModel::User_ID.'.required'=>'seller_id is required try user_id=<user_id>',
                PackagesModel::User_ID.'.exists'=>'seller_id do not match any records, please check',
                PackagesModel::CATEGORY_ID.'.required'=>'Category_id is required try category_id=<category_id>',
                PackagesModel::CATEGORY_ID.'.min'=>'At least 1 category id has to be given in array',
                PackagesModel::CATEGORY_ID.'.exists'=>'Category _id do not match any records, please check',
            ]

        ]);
        if($validation_result['result']){
            try{
            $tag_avilable=FALSE;
            $tags_id=NULL;
                if(isset($data[PackagesModel::TAG_ID])){
                    $tags_id=explode(',',$data[PackagesModel::TAG_ID]);
                    unset($data[PackagesModel::TAG_ID]);
                    $tag_avilable=TRUE;
                }
          $result=  DB::transaction(function()use($data,$tag_avilable,$tags_id) {
                $category=Category::find($data[PackagesModel::CATEGORY_ID]);
                $package = new PackagesModel($data);
                $category->packages()->save($package);
                if ($tag_avilable) {
                    $package->tags()->attach($tags_id);
                }
              return $this->successWithData("","",['package_id'=>$package->id]);
            });
            return $result;}catch(Exception $e){
                $this->error('unknown error occurred!Might wrong tag id passed, please check',520);
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
        $data=['id'=>$id];
        $validation_result=$this->my_validate(['data'=>$data,
            'rules'=>[
                'id'=>'required|exists:'.Constants::PREFIX.'packages,id'
            ],
            'messages'=>[
                'id.required'=>'package id is required to show package try in url package/<id>',
                'id.exists'=>'package id do not match any records, it not exists or already deleted'
            ]
        ]);
        if($validation_result['result']){
           return $this->response()->item(PackagesModel::find($id),$this->packageTransformer);
        }else{
            return $validation_result['error'];
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update($id)
    {
        $package=PackagesModel::where('id',$id)->first();
        if(is_null($package)){
            return $this->error('PackageId do nat match any records, please try again',404);
        }
        $data = $this->packageTransformer->requestAdapter();
        $tag_avilable=FALSE;
        $tags_id=NULL;
        if($data[PackagesModel::TAG_ID]){
            $tags_id=explode(',',$data[PackagesModel::TAG_ID]);
            unset($data[PackagesModel::TAG_ID]);
            $tag_avilable=TRUE;}
        $data=array_filter($data,'strlen'); // filter blank or null array
        if((sizeof($data)>0)||($tag_avilable)) {
           $result= DB::transaction(function()use($package,$data,$tag_avilable,$tags_id){
            try {
                if($tag_avilable){
                    $package->tags()->sync($tags_id);
                }
                $result =/*PackagesModel::where('id', $id)->*/
                    $package->update($data);
                if($result)
                {
                    return $this->success();
                }
                else
                {
                    return $this->error('Unknown error',520);
                }
            } catch (\Exception $e) {
                return $this->error($e->getMessage(), $e->getCode());
            }
        });
            return $result;
        }else{
            return $this->error('no adequate field passed',422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $user_id
     * @param $package_id
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function destroy($user_id,$package_id)
    {
        //
        $package=PackagesModel::where('id',$package_id)->where('candybrush_packages_user_id',$user_id)->first();
        if(is_null($package)){
            return $this->error('PackageId with given user_id do nat match any records, please try again',404);
        }
        $result=DB::transaction(function()use($package){
            if($package->delete()){return $this->success('package deleted successfully',200);}else{
                return $this->error('unknown error occurred!Try again',520);
            }

        });
        return $result;
    }

    /**
     * function to set package ready to publish
     * this function may also send review request to admin then it will be published on passed
     */
    public function setCompleted($id){
        $package=PackagesModel::where('id',$id)->first();
        if(is_null($package)){
            return $this->error('PackageId do nat match any records, please try again',404);
        }
        try{
            $result=DB::transaction(function()use($package){
                $package->candybrush_packages_status=PackagesModel::PENDING_APPROVAL;
                $package->update();
                /**
                 * code here to send review request to admin
                 */
                return $this->success('Your package details submitted successfully and sent to admin for review, later published');
            });
            return $result;
        }catch(Exception $e){
            return $this->error('some unknown error occurred',520);
        }
    }

    public function changePackageStatus($package_id,$status){
        $package=PackagesModel::where('id',$package_id)->first();
        if(is_null($package)){
            return $this->error('PackageId do nat match any records, please try again',404);
        }
      $result =  DB::transaction(function()use($package,$status){
          try{
              $package->candybrush_package_status=$status;
              $package->update();
           return $this->success('Package '.$package->candybrush_packages_name." status changed to ".$package->candybrush_package_status." successfully",200);
          }catch(Exception $e){
              return $this->error('Unknown error occurred! Try again',520);
          }
        });
        return $result;
    }
    public function adminDestroyPackage($package_id)
    {
        //
        $package=PackagesModel::where('id',$package_id)->first();
        if(is_null($package)){
            return $this->error('PackageId with given user_id do nat match any records, please try again',404);
        }
        $result=DB::transaction(function()use($package){
            if($package->delete()){return $this->success('package deleted successfully',200);}else{
                return $this->error('unknown error occurred!Try again',520);
            }

        });
        return $result;
    }
}
