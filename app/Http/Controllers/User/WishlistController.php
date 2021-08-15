<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wishlist;
use Carbon\Carbon;
use Auth;

class WishlistController extends Controller
{


    public function AddToWishlist(Request $request,$product_id){
        
        if(Auth::check()){

            $exists = Wishlist::where('user_id',Auth::id())->where('product_id',$product_id)->first();
            
            if(!$exists){
                Wishlist::insert([
                    'user_id' => Auth::id(),
                    'product_id' => $product_id,
                    'created_at' => Carbon::now(), 
                ]);
                return response()->json(['success' => 'Successfully Added On Your Wishlist']);

            }else{
                return response()->json(['error' => 'This Product has Already on Your Wishlist']);
            } 

        }else{
            return response()->json(['error' => 'At First Login Your Account']);
        }

    }  //end method


    public function ViewWishlist(){
        return view('frontend.wishlist.wishlist_view');  

    }

    public function GetWishlistProduct(){
        
        $wishlist = Wishlist::with('product')->where('user_id',Auth::id())->latest()->get();
        return response()->json($wishlist);
    }



    public function RemoveWishlistProduct($id){

        Wishlist::where('user_id',Auth::id())->where('id',$id)->delete();
        return response()->json(['success' => 'Successfully  Wishlist Remove']);

    }


}
