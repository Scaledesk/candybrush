<?php

namespace App\Http\Controllers;

use App\libraries\Transformers\PackagesTransformer;
use App\PackegesUserModel;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\PackagesModel;

class PackagesController extends BaseController
{
    /**
     * creating constructor
     */
    protected $packageTransformer;
    function __construct(PackagesTransformer $packageTransformer)
    {
        $this->packageTransformer = $packageTransformer;
        // $this->middleware('jwt.auth',['except'=>['authenticate']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $u_id = $request->user_id;
        $data = $this->packageTransformer->requestAdapter();
        $data=array_filter($data,'strlen'); // filter blank or null array
        $result = PackagesModel::create($data);
        /*print_r($result);
        die;*/
        $data1 = [
            'candybrush_users_packages_user_id' => $u_id,
            'candybrush_users_packages_package_id' => $result->id,
            'candybrush_users_packages_status' => 0
        ];
        $userpackage=new PackegesUserModel($data1);
//        $userpackage->save();
        try{
            $result->userPackages()->create($userpackage);
        }
        catch(\Exception $e){
            return $this->error($e->getMessage(),$e->getCode());
        }

       $this->success();



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
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {
        //
        $data = $this->packageTransformer->requestAdapter();
        dd($data);
        $data=array_filter($data,'strlen'); // filter blank or null array
        if(sizeof($data)){ try{$result=PackagesModel::where('id', $id)->update($data);}catch(\Exception $e){
            return $this->error($e->getMessage(),$e->getCode());
        }
        }else{
            return $this->error('no adequate field passed',422);
        }
        if($result)
        {
            return $this->success();
        }
        else
        {
            return $this->error('Unknown error',520);
        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
