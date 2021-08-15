<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//use Illuminate\Support\Carbon;
use Carbon\Carbon;
use App\Models\Coupon;

class CouponController extends Controller
{
    public function CouponView(){
        $coupons = Coupon::orderBy('id','DESC')->get();
        return view('backend.coupon.view_coupon',compact('coupons'));
    }


    
    public function CouponStore(Request $request){
        $request->validate([
    		'coupon_name' => 'required',
            'coupon_discount' => 'required',
            'coupon_validity' => 'required',
    		
    	]);

        Coupon::create([
            'coupon_name' => strtoupper($request->coupon_name),
            'coupon_discount' => $request->coupon_discount,
            'coupon_validity' => $request->coupon_validity,
            'created_at' => Carbon::now(),
            
        ]);
        $notification = array(
            'message' => 'coupon Inserted Successfully',
            'alert-type' => 'success'
        );

        return Redirect()->back()->with($notification);

    }  //end method



    public function CouponEdit($id){

        $coupons = Coupon::findOrFail($id);
        return view('backend.coupon.edit_coupon',compact('coupons'));
    }




    public function CouponUpdate(Request $request,$id){

        Coupon::findOrFail($id)->update([
            'coupon_name' => strtoupper($request->coupon_name),
            'coupon_discount' => $request->coupon_discount,
            'coupon_validity' => $request->coupon_validity,
            'created_at' => Carbon::now(),
            

        ]);
        $notification = array(
            'message' => 'Coupon Updated Successfully',
            'alert-type' => 'info'
        );         
        return Redirect()->route('manage-coupons')->with($notification);

    }  //end method



    public function CouponDelete($id){

        Coupon::findOrFail($id)->delete();
        $notification = array(
            'message' => 'Coupon Delete Successfully',
            'alert-type' => 'info'
        );   
        return Redirect()->back()->with($notification);

    }


}
