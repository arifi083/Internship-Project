<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use Auth;
use Carbon\Carbon; 

class ReviewController extends Controller
{

    public function ReviewStore(Request $request){
        
        $product = $request->product_id;
        $request->validate([
    		'summary' => 'required',
            'comment' => 'required',
           	
    	]);

        Review::insert([
            'product_id' => $product,
            'user_id' => Auth::id(),
            'summary' => $request->summary,
            'comment' => $request->comment,
            'rating' => $request->quality,
             'created_at' => Carbon::now()
            
        ]);
        $notification = array(
            'message' => 'Review Will Approve By Admin',
            'alert-type' => 'success'
        );

        return Redirect()->back()->with($notification);

    } //end method



    ///admin review approve

    public function PendingReview(){
        $review = Review::where('status',0)->orderBy('id','DESC')->get();
        return view('backend.review.pending_review',compact('review'));

    }


    public function ReviewApprove($id){

        Review::where('id',$id)->update(['status' => 1]);

        $notification = array(
			'message' => 'Review Approve Successfully',
			'alert-type' => 'success'
		);
		return redirect()->back()->with($notification);

    }//end method


    public function PublishReview(){
        $review = Review::where('status',1)->orderBy('id','DESC')->get();
        return view('backend.review.publish_review',compact('review'));

    }


    public function DeleteReview($id){

        Review::findOrFail($id)->delete();

        $notification = array(
			'message' => 'Review Delete Successfully',
			'alert-type' => 'success'
		);
		return redirect()->back()->with($notification);

    }//end method





}
