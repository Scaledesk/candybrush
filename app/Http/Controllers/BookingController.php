<?php

namespace App\Http\Controllers;

use App\Booking;
use App\libraries\Transformers\BookingTransformer;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
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
                Booking::PACKAGE_ID.'.exists'=>'Only numbers are allowed as package_id, please check'
            ]
        ]);
        if($validation_result['result']){
                $result=DB::transaction(function()use($data){
                    try{
                        $booking=new Booking($data);
                        if($booking->save()){
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
        return $this->response()->item(Booking::find($id)->first(),$this->booking_transformer);
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
}
