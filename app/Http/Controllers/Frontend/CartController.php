<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Session;
use App\Models\Product;
use App\Models\ShipDivision;
use App\Models\Coupon;
use Carbon\Carbon;
use Auth;


class CartController extends Controller
{
     public function AddToCart(Request $request,$id){

        if (Session::has('coupon')) {
            Session::forget('coupon');
         }

        $product = Product::findOrFail($id);

        if($product->discount_price == NULL){
            Cart::add([
                'id' => $id, 
                'name' => $request->product_name, 
                'qty' => $request->quantity, 
                'price' => $product->selling_price, 
                'weight' => 1, 
                'options' => [
                    'image' => $product->product_thambnail,
                    'color' => $request->color,
                    'size' => $request->size,

                ],


            ]);
            return response()->json(['success' => 'Successfully Added on Your Cart']);

        }else{
            Cart::add([
                'id' => $id, 
                'name' => $request->product_name, 
                'qty' => $request->quantity, 
                'price' => $product->discount_price, 
                'weight' => 1, 
                'options' => [
                    'image' => $product->product_thambnail,
                    'color' => $request->color,
                    'size' => $request->size,

                ],


            ]);

            return response()->json(['success' => 'Successfully Added on Your Cart']);
        }


    }  //end method


    public function AddMiniCart(){
        $carts = Cart::content();
        $cartQty = Cart::count();
        $cartTotal = Cart::total();

        return response()->json(array(
            'carts' => $carts,
            'cartQty' => $cartQty,
            'cartTotal' => round($cartTotal),
        ));
    }


    public function RemoveMiniCart($rowId){
        Cart::remove($rowId);
        return response()->json(['success' => 'Product Remove from Cart']);
    }




    public function CouponApply(Request $request){

        $coupon = Coupon::where('coupon_name',$request->coupon_name)->where('coupon_validity','>=',Carbon::now()->format('Y-m-d'))->first();
        if($coupon){
            
            Session::put('coupon',[
                'coupon_name' => $coupon->coupon_name,
                'coupon_discount' => $coupon->coupon_discount,
                'discount_amount' => round(Cart::total() * $coupon->coupon_discount/100), 
                'total_amount' => round(Cart::total() - Cart::total() * $coupon->coupon_discount/100)  
            ]);
 

            return response()->json(array(

                'success' => 'Coupon Applied Successfully'
            ));
            
        }
        else{
            return response()->json(['error' => 'Invalid Coupon']);
        }

    } //end method



    public function CouponCalculation(){

        if (Session::has('coupon')) {
            return response()->json(array(
                'subtotal' => Cart::total(),
                'coupon_name' => session()->get('coupon')['coupon_name'],
                'coupon_discount' => session()->get('coupon')['coupon_discount'],
                'discount_amount' => session()->get('coupon')['discount_amount'],
                'total_amount' => session()->get('coupon')['total_amount'],
            ));
        }else{
            return response()->json(array(
                'total' => Cart::total(),
            ));

        }
    } // end method 


    public function CouponRemove(){
        Session::forget('coupon');
        return response()->json(['success' => 'Coupon Remove Successfully']);
    }





    //checkout method
    public function CheckoutCreate(){
        if(Auth::check()){
            if(Cart::total() > 0){

                $carts = Cart::content();
                $cartQty = Cart::count(); 
                $cartTotal = Cart::total();
 
                $divisions = ShipDivision::orderBy('division_name','ASC')->get();

                return view('frontend.checkout.checkout_view',compact('carts','cartQty','cartTotal','divisions'));
            }else{

                $notification = array(
                    'message' => 'shopping at least one product',
                    'alert-type' => 'error'
                );         
                return Redirect()->to('/')->with($notification);

            }

        }else{
            $notification = array(
                'message' => 'you nedd to login first',
                'alert-type' => 'error'
            );         
            return Redirect()->route('login')->with($notification);

        }
    }
   



}
