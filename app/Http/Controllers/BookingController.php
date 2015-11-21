<?php

namespace App\Http\Controllers;

use App\Addon;
use App\Booking;
use App\Booking_Packages_Addons;
use App\Booking_Packages_Installments;
use App\Installment;
use App\libraries\Transformers\BookingTransformer;
use App\PackagesModel;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Mockery\CountValidator\Exception;


class BookingController extends BaseController
{
    protected $booking_transformer;

    function __construct()
    {
        $this->booking_transformer = new BookingTransformer();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return $this->response()->collection(Booking::all(),$this->booking_transformer);
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
        //
        $data=$this->booking_transformer->requestAdaptor();
        $validation_result=$this->my_validate([
            'data'=>$data,
            'rules'=>[
                Booking::BUYER_ID=>'required|exists:users,id|numeric',
                Booking::PACKAGE_ID=>'required|exists:candybrush_packages,id|numeric',
                Booking::PAYMENT_TYPE=>'required',
                Booking::PACKAGE_TIMESTAMP=>"required"
            ],
            'messages'=>[
                Booking::BUYER_ID.'.required'=>'User_id is require try user_id=<user_id>',
                Booking::PACKAGE_ID.'.required'=>'Package_id is required try package_id=<package_id>',
                Booking::PAYMENT_TYPE.'.required'=>'payment type is required try payment_type=<payment_type>',
                Booking::BUYER_ID.'.exists'=>'user_id do not match any records, please check',
                Booking::PACKAGE_ID.'.exists'=>'package id do not match any records, please check',
                Booking::BUYER_ID.'.numeric'=>'Only numbers are allowed as user_id, please check',
                Booking::PACKAGE_ID.'.exists'=>'Only numbers are allowed as package_id, please check',
                Booking::PACKAGE_TIMESTAMP.'.required'=>"package Timestamp is required"
            ]
        ]);
        if($validation_result['result']){
                $result=DB::transaction(function()use($data){
                    try{
                    $package=PackagesModel::where('id',$data[Booking::PACKAGE_ID])->first();
                        $updated_at=DB::table('candybrush_packages')->where('id',$data[Booking::PACKAGE_ID])->select('updated_at')->first()->updated_at;
                        if($data[Booking::PACKAGE_TIMESTAMP]!=$updated_at){
                            return $this->error('Package updated before order placed',422);
                        }
                    $data['candybrush_bookings_seller_id']=$package->candybrush_packages_user_id;
                    $data['candybrush_bookings_price']=$package->candybrush_packages_price;
                    $data['candybrush_bookings_deal_price']=$package->candybrush_packages_deal_price;
                    $data['candybrush_bookings_package_title']=$package->candybrush_packages_name;
                    $data['candybrush_bookings_package_description']=$package->candybrush_packages_description;
                    $data['candybrush_bookings_package_category_id']=$package->candybrush_packages_category_id;
                    $data['candybrush_bookings_package_average_rating']=$package->candybrush_packages_average_rating;
                    $data['candybrush_bookings_package_available_dates']=$package->candybrush_packages_available_dates;
                    $data['candybrush_bookings_package_term_condition']=$package->candybrush_packages_term_condition;
                    $data['candybrush_bookings_package_delivery_time']=$package->candybrush_packages_delivery_time;
                    $data['candybrush_bookings_package_delivery_time_type']=$package->candybrush_packages_delivery_time_type;
                    $data['candybrush_bookings_package_instructions']=$package->candybrush_packages_instructions;
                    $data['candybrush_bookings_package_location']=$package->candybrush_packages_location;
                    $data['candybrush_bookings_package_meeting_availability']=$package->candybrush_packages_meeting_availability;
                    $data['candybrush_bookings_package_meeting_address']=$package->candybrush_packages_meeting_address;
                    $data['candybrush_bookings_package_delivery_type_id']=$package->candybrush_packages_delivery_type_id;
                    $data['candybrush_bookings_package_delivery_type_name']=$package->candybrush_packages_delivery_type_name;
                    $data['candybrush_bookings_package_payment_type_id']=$package->candybrush_packages_payment_type_id;
                    $data['candybrush_bookings_package_payment_type_name']=$package->candybrush_packages_payment_type_name;
                    $data['candybrush_bookings_package_type_id']=$package->candybrush_package_package_type_id;
                    $data['candybrush_bookings_package_type_name']=$package->candybrush_package_package_type_name;
                        $booking=new Booking($data);
                        if($booking->save()){
                            $addons=$data['addons_id'];
                            $bonus=$data['bonus_id'];
//                            $photos=$data['photos_id']; // currently we do not save photos in bookings table
                            $installments=$data['installments_id'];
                            unset($data['addons_id']);
                            unset($data['bonus_id']);
//                            unset($data['photos_id']); // currently we do not save photos in bookings table
                            unset($data['installments_id']);
                            $check=function(){
                            //  will be implemented for if used as follows in addons bonus etc
                            };
                            if($addons!='none'){
                                $addons=explode(',',$addons);
                                foreach($addons as $addon_id){
                                    $addon=Addon::where('candybrush_addons_id', '=', $addon_id)->where('candybrush_addons_package_id',$package->id)->first();
                                    if(is_null($addon)) {
                                        return $this->error('Addon not found',422);
                                    }
                                    $obj=array();
                                    $obj['candybrush_bookings_addons_addon_id']=$addon['candybrush_addons_id'];
                                    $obj['candybrush_bookings_addons_name']=$addon['candybrush_addons_name'];
                                    $obj['candybrush_bookings_addons_description']=$addon['candybrush_addons_description'];
                                    $obj['candybrush_bookings_addons_package_id']=$addon['candybrush_addons_package_id'];
                                    $obj['candybrush_bookings_addons_amount']=$addon['candybrush_addons_amount'];
                                    $obj['candybrush_bookings_addons_days']=$addon['candybrush_addons_days'];
                                    $obj['candybrush_bookings_addons_terms']=$addon['candybrush_addons_terms'];
                                    $obj['candybrush_bookings_addons_bookings_id']=$booking->candybrush_bookings_id;
                                    Booking_Packages_Addons::create($obj);
                                    unset($obj,$addon);
                                }
                            }
                            if($installments!="none"){

                                $installments=explode(',',$installments);
                                foreach($installments as $installment_id){
                                    $installment=Installment::where('candybrush_packages_installments_id', '=', $installment_id)->where('candybrush_packages_installments_packages_id',$package->id)->first();
                                    if(is_null($installment)) {
                                        return $this->error('Installment not found',422);
                                    }
                                    $obj=array();
                                    $obj['candybrush_bookings_packages_installments_installment_id']=$installment['candybrush_packages_installments_id'];
                                    $obj['candybrush_bookings_packages_installments_packages_id']=$installment['candybrush_packages_installments_packages_id'];
                                    $obj['candybrush_bookings_packages_installments_installment_number']=$installment['candybrush_packages_installments_installment_number'];
                                    $obj['candybrush_bookings_packages_installments_installment_amount']=$installment['candybrush_packages_installments_installment_amount'];
                                    $obj['candybrush_bookings_packages_installments_bookings_id']=$booking->candybrush_bookings_id;
                                    Booking_Packages_Installments::create($obj);
                                    unset($obj,$installment);
                                }
                            }
                            return $this->success();
                        }else{
                            return $this->error('Some error occurred',520);
                        }
                    }catch(Exception $e){
                     return  $this->error('unknown error occurred',520);
                    }
                });
                return $result;
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
        $booking=Booking::where('candybrush_bookings_id',$id)->first();
        if(is_null($booking)){return $this->error('Booking id do not match any records');}
        return $this->response()->item($booking,$this->booking_transformer);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $booking=Booking::where('candybrush_bookings_id',$id)->first();
        if(is_null($booking)){
            return $this->error('Booking id do not match any records, please check');
        }
        $data=$this->booking_transformer->requestAdaptor();
        $validation_result=$this->my_validate([
            'data'=>$data,
            'rules'=>[
                Booking::USER_ID=>'required|exists:users,id|numeric',
                Booking::PACKAGE_ID=>'required|exists:candybrush_packages,id|numeric',
                Booking::PAYMENT_TYPE=>'required',
            ],
            'messages'=>[
                Booking::USER_ID.'.required'=>'User_id is require try user_id=<user_id>',
                Booking::PACKAGE_ID.'.required'=>'Package_id is required try package_id=<package_id>',
                Booking::PAYMENT_TYPE.'.required'=>'payment type is required try payment_type=<payment_type>',
                Booking::USER_ID.'.exists'=>'user_id do not match any records, please check',
                Booking::PACKAGE_ID.'.exists'=>'package id do not match any records, please check',
                Booking::USER_ID.'.numeric'=>'Only numbers are allowed as user_id, please check',
                Booking::PACKAGE_ID.'.numeric'=>'Only numbers are allowed as package_id, please check'
            ]
        ]);
        if($validation_result['result']){
            $result=DB::transaction(function()use($data,$booking){
                try{
                    $booking->update($data);
                        return $this->success();
                    }catch(Exception $e){
                    return  $this->error('unknown error occurred',520);
                }
            });
            return $result;
        }else{
            return $validation_result['error'];
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

    }
    public function confirmPaymentStatus($id){
        $booking=Booking::where('candybrush_bookings_id',$id)->first();
        if(is_null($booking)){
            return $this->error('Booking id do not match any records, please check');
        }
       $result= DB::transaction(function()use($booking){
            try{
                $booking->candybrush_bookings_payment_status="CONFIRMED";
                $booking->save();
                return $this->success('Payment confirmed successfully');
            }catch(Exception $e){
                return $this->error('unknown error occurred');
            }
        });
        return $result;
    }
    public function bookingCount(){
        return Response::json([
            "count"=>Booking::get()->Count()
        ]);
    }
}
