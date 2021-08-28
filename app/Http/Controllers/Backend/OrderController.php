<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Auth;
use PDF;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function PendingOrders(){
        $orders = Order::where('status','pending')->orderBy('id','DESC')->get();
        return view('backend.orders.pending_orders',compact('orders'));

    }//end method


    public function PendingOrdersDetails($order_id){
        $order = Order::with('division','district','state','user')->where('id',$order_id)->first();
        $orderItem  = OrderItem::with('product')->where('order_id',$order_id)->orderBy('id','DESC')->get();
        return view('backend.orders.pending_order_details',compact('order','orderItem'));

    } //end method




    public function ConfirmedOrders(){
		$orders = Order::where('status','confirm')->orderBy('id','DESC')->get();
		return view('backend.orders.confirmed_orders',compact('orders'));

	} // end mehtod


    public function ProcessingOrders(){
		$orders = Order::where('status','processing')->orderBy('id','DESC')->get();
		return view('backend.orders.processing_orders',compact('orders'));

	} // end mehtod 


    public function PickedOrders(){
		$orders = Order::where('status','picked')->orderBy('id','DESC')->get();
		return view('backend.orders.picked_orders',compact('orders'));

	} // end mehtod 

    public function ShippedOrders(){
		$orders = Order::where('status','shipped')->orderBy('id','DESC')->get();
		return view('backend.orders.shipped_orders',compact('orders'));

	} // end mehtod 
    public function DeliveredOrders(){
		$orders = Order::where('status','delivered')->orderBy('id','DESC')->get();
		return view('backend.orders.delivered_orders',compact('orders'));

	} // end mehtod 
    public function CancelOrders(){
		$orders = Order::where('status','cancel')->orderBy('id','DESC')->get();
		return view('backend.orders.cancel_orders',compact('orders'));

	} // end mehtod 




	//updated status



    //pending to confirm
	public function PendingToConfirm($order_id){
		Order::findOrFail($order_id)->update(['status' => 'confirm']);
		$notification = array(
			'message' => 'Order Confirm Successfully',
			'alert-type' => 'success'
		);
		return redirect()->route('pending-orders')->with($notification);


	}

    
    //confirm to proceesing
	public function ConfirmToProcessing($order_id){
		Order::findOrFail($order_id)->update(['status' => 'processing']);
		$notification = array(
			'message' => 'Order Processing Successfully',
			'alert-type' => 'success'
		);
		return redirect()->route('confirmed-orders')->with($notification);


	}


	//proceesing to picked
	public function ProcessingToPicked($order_id){
		Order::findOrFail($order_id)->update(['status' => 'picked']);
		$notification = array(
			'message' => 'Order Picked Successfully',
			'alert-type' => 'success'
		);
		return redirect()->route('processing-orders')->with($notification);


	}


	// picked to shipped
	public function PickedToShipped($order_id){
		Order::findOrFail($order_id)->update(['status' => 'shipped']);
		$notification = array(
			'message' => 'Order Shipped Successfully',
			'alert-type' => 'success'
		);
		return redirect()->route('picked-orders')->with($notification);


	}


	//  shipped to delivered
	public function ShippedToDelivered($order_id){
		Order::findOrFail($order_id)->update(['status' => 'delivered']);
		$notification = array(
			'message' => 'Order Delivered Successfully',
			'alert-type' => 'success'
		);
		return redirect()->route('shipped-orders')->with($notification);


	}


     // delivered to cancel
	public function DeliveredToCancel($order_id){
		Order::findOrFail($order_id)->update(['status' => 'cancel']);
		$notification = array(
			'message' => 'Order Cancel Successfully',
			'alert-type' => 'success'
		);
		return redirect()->route('delivered-orders')->with($notification);


	}



	//admin invoice download
	public function AdminInvoiceDownload($order_id){

        $order = Order::with('division','district','state','user')->where('id',$order_id)->first();
        $orderItem  = OrderItem::with('product')->where('order_id',$order_id)->orderBy('id','DESC')->get();
        //return view('frontend.user.order.order_invoice',compact('order','orderItem'));
        $pdf = PDF::loadView('backend.orders.order_invoice',compact('order','orderItem'))->setPaper('a4')->setOptions([
            'tempDir' => public_path(),
			'chroot' => public_path(),
        ]);
        return $pdf->download('invoice.pdf');

    } //end method



}
